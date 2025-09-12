<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::with('order')->paginate(10);
        return view('frontend.admin.dashboard.shipping.shipping', compact('shippings'));
    }

    public function create()
    {
        $orders = Order::all(); 
        return view('frontend.admin.dashboard.shipping.forms_shipping', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'carrier' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'estimated_delivery' => 'nullable|date',
            'actual_delivery' => 'nullable|date',
            'shipping_cost' => 'required|numeric',
        ]);

        Shipping::create($request->all());

        return redirect()->route('admin.shippings.index')->with('success', 'تم إضافة الشحنة بنجاح');
    }

    public function edit(Shipping $shipping)
    {
        $orders = Order::all();
        return view('frontend.admin.dashboard.shipping.forms_shipping', compact('shipping', 'orders'));
    }

    public function update(Request $request, Shipping $shipping)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'carrier' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'estimated_delivery' => 'nullable|date',
            'actual_delivery' => 'nullable|date',
            'shipping_cost' => 'required|numeric',
        ]);

        $shipping->update($request->all());

        return redirect()->route('admin.shippings.index')->with('success', 'تم تحديث الشحنة بنجاح');
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return redirect()->route('admin.shippings.index')->with('success', 'تم حذف الشحنة بنجاح');
    }
}
