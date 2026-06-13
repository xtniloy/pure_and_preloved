<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\OrderStatusUpdateEmailJob;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->withCount('items')->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('reference', 'like', '%' . $q . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20)->appends([
            'q' => $request->q,
            'status' => $request->status,
        ]);

        $statuses = Order::STATUSES;

        return view('admin.sections.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        $statuses = Order::STATUSES;

        return view('admin.sections.orders.show', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|string|in:' . implode(',', Order::STATUSES),
        ]);

        $statusChanged = $order->status !== $data['status'];

        $order->update([
            'status' => $data['status'],
        ]);

        // Notify the customer when the status actually changes (covers status
        // updates and the "shipped" shipping notification).
        if ($statusChanged) {
            try {
                OrderStatusUpdateEmailJob::dispatch($order);
            } catch (\Throwable $e) {
                Log::error('Order status update email dispatch failed: ' . $e->getMessage());
            }
        }

        $message = $statusChanged
            ? 'Order status updated and the customer has been notified by email.'
            : 'Order updated successfully';

        return redirect()->route('admin.orders.show', $order)->with('success', $message);
    }
}

