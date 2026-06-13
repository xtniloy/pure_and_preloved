<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * The authenticated customer's order history ("My Orders").
     */
    public function index(): View
    {
        $orders = Auth::user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('user.account.orders', compact('orders'));
    }

    /**
     * A single order belonging to the authenticated customer.
     * Scoped through the user relation so customers can only view their own orders.
     */
    public function show(string $reference): View
    {
        $order = Auth::user()
            ->orders()
            ->where('reference', $reference)
            ->with('items')
            ->firstOrFail();

        return view('user.account.order_detail', compact('order'));
    }
}
