<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::latest()->paginate(10);
        return view('admin.sections.shipping_methods.index', compact('shippingMethods'));
    }

    public function create()
    {
        return view('admin.sections.shipping_methods.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
            'status' => 'nullable|boolean',
        ]);

        ShippingMethod::create([
            'name' => $request->name,
            'charge' => $request->charge,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.shipping_methods.index')->with('success', 'Shipping method created successfully.');
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.sections.shipping_methods.form', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
            'status' => 'nullable|boolean',
        ]);

        $shippingMethod->update([
            'name' => $request->name,
            'charge' => $request->charge,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.shipping_methods.index')->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();
        return redirect()->route('admin.shipping_methods.index')->with('success', 'Shipping method deleted successfully.');
    }
}
