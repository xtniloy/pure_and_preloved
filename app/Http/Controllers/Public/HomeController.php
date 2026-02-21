<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('public.home.index');
    }

    public function product(){
        return view('public.home.product');
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
}
