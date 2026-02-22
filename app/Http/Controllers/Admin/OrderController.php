<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

        $statuses = [
            'pending',
            'processing',
            'paid',
            'shipped',
            'completed',
            'cancelled',
        ];

        return view('admin.sections.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        $statuses = [
            'pending',
            'processing',
            'paid',
            'shipped',
            'completed',
            'cancelled',
        ];

        return view('admin.sections.orders.show', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,processing,paid,shipped,completed,cancelled',
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully');
    }
}

