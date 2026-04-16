<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $featuredProducts = Product::with(['categories', 'thumbnailImage'])
            ->where('status', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('public.home.index', compact('featuredProducts'));
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

        $productId = (int) $data['product_id'];
        $quantity = isset($data['quantity']) ? (int) $data['quantity'] : 1;

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
            ];
        }

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

        if (!empty($data['quantities'])) {
            foreach ($data['quantities'] as $productId => $quantity) {
                $productId = (int) $productId;
                $quantity = (int) $quantity;

                if ($quantity <= 0) {
                    unset($cart[$productId]);
                } else {
                    $cart[$productId]['quantity'] = $quantity;
                }
            }
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
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
        $items = [];
        $subtotal = 0;

        $itemsInput = $request->input('items', []);

        if (!empty($itemsInput) && is_array($itemsInput)) {
            $productIds = array_map('intval', array_keys($itemsInput));
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($itemsInput as $productId => $quantity) {
                $productId = (int) $productId;
                $quantity = (int) $quantity;

                if ($quantity < 1 || !isset($products[$productId])) {
                    continue;
                }

                $product = $products[$productId];
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
        ]);

        $shippingMethod = \App\Models\ShippingMethod::find($data['shipping_method_id']);
        $shippingCharge = $shippingMethod->charge;
        $grandTotal = $subtotal + $shippingCharge;

        $user = Auth::user();

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
        }

        $request->session()->forget('cart');

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

    public function terms()
    {
        return view('public.home.terms');
    }
}
