<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Status filters available on the order history page.
     */
    private const STATUS_FILTERS = ['in_progress', 'completed', 'cancelled'];

    /**
     * The authenticated customer's order history ("My Orders").
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $status = $request->query('status');
        if (!in_array($status, self::STATUS_FILTERS, true)) {
            $status = null;
        }

        $counts = [
            'all' => $user->orders()->count(),
            'in_progress' => $user->orders()->whereIn('status', Order::OPEN_STATUSES)->count(),
            'completed' => $user->orders()->where('status', 'completed')->count(),
            'cancelled' => $user->orders()->where('status', 'cancelled')->count(),
        ];

        $orders = $user->orders()
            ->withCount('items')
            ->when($status === 'in_progress', fn ($query) => $query->whereIn('status', Order::OPEN_STATUSES))
            ->when($status === 'completed', fn ($query) => $query->where('status', 'completed'))
            ->when($status === 'cancelled', fn ($query) => $query->where('status', 'cancelled'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('user.account.orders', compact('orders', 'counts', 'status'));
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
            ->with('items.product.thumbnailImage')
            ->firstOrFail();

        return view('user.account.order_detail', compact('order'));
    }
}
