<?php

namespace App\Http\Controllers\Public;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Jobs\OrderConfirmationEmailJob;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Setting;
use Modules\Files\Models\Asset;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\NewOrderPlaced;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $sections = HomeSection::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        HomeSection::resolveImages($sections);

        // Products are only queried when a featured-products section is on the page.
        $featuredProducts = collect();
        if ($sections->contains(fn ($section) => $section->type === 'featured_products')) {
            $featuredProducts = Product::with(['categories', 'thumbnailImage'])
                ->where('status', true)
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        $settings = Setting::getMany([
            'home_meta_title',
            'home_meta_description',
            'home_meta_keywords',
            'home_meta_image_id',
        ]);

        $metaImageUrl = null;
        if (!empty($settings['home_meta_image_id'])) {
            $metaImageUrl = Asset::find($settings['home_meta_image_id'])?->public_url;
        }

        $seo = [
            'meta_title' => $settings['home_meta_title'] ?? null,
            'meta_description' => $settings['home_meta_description'] ?? null,
            'meta_keywords' => $settings['home_meta_keywords'] ?? null,
            'meta_image_url' => $metaImageUrl,
        ];

        return view('public.home.index', compact('sections', 'featuredProducts', 'seo'));
    }

    public function cart(Request $request){
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return view('public.home.cart', [
                'cartItems' => [],
                'cartSubtotal' => 0,
            ]);
        }

        $productIds = array_keys($cart);
        $products = Product::with(['categories', 'thumbnailImage'])->whereIn('id', $productIds)->get();

        $cartItems = [];

        foreach ($products as $product) {
            $quantity = $cart[$product->id]['quantity'] ?? 0;

            if ($quantity < 1) {
                continue;
            }

            $unitPrice = $product->sale_price ?? $product->price;
            $subtotal = $unitPrice * $quantity;

            $category = $product->categories->first();
            $gender = $category ? $category->gender : null;
            $categorySlug = $category ? $category->slug : null;

            $imageUrl = null;
            if ($product->thumbnailImage) {
                $imageUrl = $product->thumbnailImage->public_url;
            } elseif ($product->main_image) {
                $imageUrl = $product->main_image->public_url;
            } else {
                $imageUrl = asset('assets/images/product-image/8.jpg');
            }

            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'gender' => $gender,
                'category_slug' => $categorySlug,
                'image_url' => $imageUrl,
            ];
        }

        $cartSubtotal = collect($cartItems)->sum('subtotal');

        return view('public.home.cart', [
            'cartItems' => $cartItems,
            'cartSubtotal' => $cartSubtotal,
        ]);
    }

    public function wishlist(Request $request)
    {
        $wishlist = $request->session()->get('wishlist', []);

        if (empty($wishlist)) {
            return view('public.home.wishlist', [
                'wishlistItems' => [],
            ]);
        }

        $productIds = array_keys($wishlist);
        $products = Product::with(['categories', 'thumbnailImage'])->whereIn('id', $productIds)->get();

        $wishlistItems = [];

        foreach ($products as $product) {
            $category = $product->categories->first();
            $gender = $category ? $category->gender : null;
            $categorySlug = $category ? $category->slug : null;

            $imageUrl = null;
            if ($product->thumbnailImage) {
                $imageUrl = $product->thumbnailImage->public_url;
            } elseif ($product->main_image) {
                $imageUrl = $product->main_image->public_url;
            } else {
                $imageUrl = asset('assets/images/product-image/8.jpg');
            }

            $wishlistItems[] = [
                'product' => $product,
                'gender' => $gender,
                'category_slug' => $categorySlug,
                'image_url' => $imageUrl,
            ];
        }

        return view('public.home.wishlist', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    public function checkout(Request $request)
    {
        $items = [];
        $subtotal = 0;

        if ($request->filled('product_id')) {
            $productId = (int) $request->input('product_id');
            $quantity = (int) $request->input('quantity', 1);
            if ($quantity < 1) {
                $quantity = 1;
            }

            $product = Product::where('id', $productId)->where('status', true)->firstOrFail();

            $unitPrice = $product->sale_price ?? $product->price;
            $lineTotal = $unitPrice * $quantity;
            $subtotal = $lineTotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        } else {
            $cart = $request->session()->get('cart', []);

            if (!empty($cart)) {
                $productIds = array_keys($cart);
                $products = Product::whereIn('id', $productIds)->get();

                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 0;
                    if ($quantity < 1) {
                        continue;
                    }

                    $unitPrice = $product->sale_price ?? $product->price;
                    $lineTotal = $unitPrice * $quantity;
                    $subtotal += $lineTotal;

                    $items[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                    ];
                }
            }
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();
        $shippingMethods = \App\Models\ShippingMethod::where('status', true)->get();

        return view('public.home.checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'user' => $user,
            'shippingMethods' => $shippingMethods,
        ]);
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::where('id', $data['product_id'])->where('status', true)->first();

        if (!$product) {
            return back()->with('error', 'This product is not available.');
        }

        if (!$product->in_stock) {
            return back()->with('error', '"' . $product->name . '" is out of stock.');
        }

        $quantity = isset($data['quantity']) ? (int) $data['quantity'] : 1;

        $cart = $request->session()->get('cart', []);
        $current = $cart[$product->id]['quantity'] ?? 0;
        $desired = $current + $quantity;

        // Never let the cart hold more than what's in stock.
        if ($desired > $product->stock) {
            $cart[$product->id]['quantity'] = $product->stock;
            $request->session()->put('cart', $cart);

            return redirect()->route('cart.index')
                ->with('error', 'Only ' . $product->stock . ' of "' . $product->name . '" are available; your cart was set to the maximum.');
        }

        $cart[$product->id]['quantity'] = $desired;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function addToWishlist(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = (int) $data['product_id'];

        $wishlist = $request->session()->get('wishlist', []);
        $wishlist[$productId] = true;
        $request->session()->put('wishlist', $wishlist);

        return back()->with('success', 'Product added to wishlist.');
    }

    public function removeFromWishlist(Request $request, Product $product)
    {
        $wishlist = $request->session()->get('wishlist', []);

        if (isset($wishlist[$product->id])) {
            unset($wishlist[$product->id]);
            $request->session()->put('wishlist', $wishlist);
        }

        return redirect()->route('wishlist.index');
    }

    public function updateCart(Request $request)
    {
        $data = $request->validate([
            'quantities' => 'nullable|array',
            'quantities.*' => 'nullable|integer|min:0',
        ]);

        $cart = $request->session()->get('cart', []);
        $messages = [];

        if (!empty($data['quantities'])) {
            $productIds = array_map('intval', array_keys($data['quantities']));
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($data['quantities'] as $productId => $quantity) {
                $productId = (int) $productId;
                $quantity = (int) $quantity;
                $product = $products[$productId] ?? null;

                // Drop unknown products and zero/empty quantities from the cart.
                if (!$product || $quantity <= 0) {
                    unset($cart[$productId]);
                    continue;
                }

                // Clamp to available stock.
                if ($quantity > $product->stock) {
                    $quantity = $product->stock;
                    $messages[] = 'Only ' . $product->stock . ' of "' . $product->name . '" available.';
                }

                if ($quantity <= 0) {
                    unset($cart[$productId]);
                } else {
                    $cart[$productId]['quantity'] = $quantity;
                }
            }
        }

        $request->session()->put('cart', $cart);

        $redirect = redirect()->route('cart.index');

        if (!empty($messages)) {
            $redirect->with('error', implode(' ', array_unique($messages)));
        }

        return $redirect;
    }

    public function removeFromCart(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function clearCart(Request $request)
    {
        $request->session()->forget('cart');

        return redirect()->route('cart.index');
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:1000',
            'billing_city' => 'required|string|max:255',
            'billing_postcode' => 'required|string|max:50',
            'billing_country' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:50',
            'billing_email' => 'required|email:rfc,dns,filter|max:255',
            'notes' => 'nullable|string|max:2000',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'items' => 'nullable|array',
            'items.*' => 'integer|min:1',
        ]);

        // Build the desired quantities map (productId => quantity) from the
        // submitted "buy now" items, or fall back to the session cart.
        $desired = [];
        $itemsInput = $request->input('items', []);

        if (!empty($itemsInput) && is_array($itemsInput)) {
            foreach ($itemsInput as $productId => $quantity) {
                $productId = (int) $productId;
                $quantity = (int) $quantity;
                if ($productId > 0 && $quantity > 0) {
                    $desired[$productId] = ($desired[$productId] ?? 0) + $quantity;
                }
            }
        } else {
            $cart = $request->session()->get('cart', []);
            foreach ($cart as $productId => $row) {
                $productId = (int) $productId;
                $quantity = (int) ($row['quantity'] ?? 0);
                if ($productId > 0 && $quantity > 0) {
                    $desired[$productId] = $quantity;
                }
            }
        }

        if (empty($desired)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $shippingMethod = \App\Models\ShippingMethod::find($data['shipping_method_id']);
        $user = Auth::user();

        try {
            $order = DB::transaction(function () use ($desired, $data, $shippingMethod, $user) {
                // Lock the rows we're about to sell so concurrent checkouts
                // can't oversell the same stock.
                $products = Product::whereIn('id', array_keys($desired))
                    ->where('status', true)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $items = [];
                $subtotal = 0;
                $errors = [];

                foreach ($desired as $productId => $quantity) {
                    $product = $products[$productId] ?? null;

                    if (!$product) {
                        $errors[] = 'A product in your cart is no longer available.';
                        continue;
                    }

                    if ($product->stock < $quantity) {
                        $errors[] = $product->stock > 0
                            ? 'Only ' . $product->stock . ' left in stock for "' . $product->name . '".'
                            : '"' . $product->name . '" is out of stock.';
                        continue;
                    }

                    // Price always comes from the DB, never the request.
                    $unitPrice = $product->sale_price ?? $product->price;
                    $lineTotal = $unitPrice * $quantity;
                    $subtotal += $lineTotal;

                    $items[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                    ];
                }

                if (!empty($errors)) {
                    // Rolls back the transaction.
                    throw new InsufficientStockException(implode(' ', array_unique($errors)));
                }

                $shippingCharge = $shippingMethod->charge;
                $grandTotal = $subtotal + $shippingCharge;

                $order = Order::create([
                    'user_id' => $user ? $user->id : null,
                    'reference' => 'ORD-' . strtoupper(Str::random(10)),
                    'subtotal' => $subtotal,
                    'shipping_method' => $shippingMethod->name,
                    'shipping_charge' => $shippingCharge,
                    'total' => $grandTotal,
                    'status' => 'pending',
                    'billing_first_name' => $data['billing_first_name'],
                    'billing_last_name' => $data['billing_last_name'],
                    'billing_address' => $data['billing_address'],
                    'billing_city' => $data['billing_city'],
                    'billing_postcode' => $data['billing_postcode'],
                    'billing_country' => $data['billing_country'],
                    'billing_phone' => $data['billing_phone'],
                    'billing_email' => $data['billing_email'],
                    'notes' => $data['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'product_sku' => $item['product']->sku,
                        'unit_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'line_total' => $item['line_total'],
                    ]);

                    // Decrement stock for the sold quantity.
                    $item['product']->decrement('stock', $item['quantity']);
                }

                return $order;
            });
        } catch (InsufficientStockException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $request->session()->forget('cart');

        // Send the order confirmation email (queued). A queue/mail hiccup must
        // not break the successful checkout, so failures are logged, not thrown.
        try {
            OrderConfirmationEmailJob::dispatch($order);
        } catch (\Throwable $e) {
            Log::error('Order confirmation email dispatch failed: ' . $e->getMessage());
        }

        // Notify admins of the new order (email + web, per their preferences).
        try {
            app(AdminNotificationService::class)->notifyAdmins(new NewOrderPlaced($order));
        } catch (\Throwable $e) {
            Log::error('New order admin notification failed: ' . $e->getMessage());
        }

        return redirect()->route('order.success', $order->reference);
    }

    public function orderSuccess($reference)
    {
        $order = Order::with('items.product')->where('reference', $reference)->firstOrFail();

        return view('public.home.order_success', compact('order'));
    }

    public function quickView(Product $product)
    {
        $product->load(['categories', 'thumbnailImage']);

        $category = $product->categories->first();
        $gender = $category ? $category->gender : null;
        $categorySlug = $category ? $category->slug : null;

        return view('public.modal.quickview_content', compact('product', 'gender', 'categorySlug'));
    }
}
