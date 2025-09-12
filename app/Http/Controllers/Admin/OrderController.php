<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Product;

class OrderController extends Controller
{
    // ============================
    // عرض جميع الطلبات
    // ============================
     public function index()
    {
        $orders = Order::with('customer.user')->orderBy('order_id','desc')->paginate(15);
        return view('frontend.admin.dashboard.orders.orders_all', compact('orders'));
    }

    // ============================
    // عرض صفحة إنشاء طلب
    // ============================
    public function create()
    {
        $customers = Customer::with('user')->get();
        $products  = Product::all();
        return view('frontend.admin.dashboard.orders.form_order', compact('customers','products'));
    }

    // ============================
    // حفظ طلب جديد
    // ============================
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_number' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'shipping_amount' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'payment_method' => 'nullable|string',
            'order_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if(empty($data['order_number'])){
            $data['order_number'] = 'ORD-' . time();
        }

        $order = Order::create($data);

        // حفظ عناصر الطلب
        if($request->items){
            foreach($request->items as $item){
                if(empty($item['product_id'])) continue;
                $product = Product::find($item['product_id']);
                $unitPrice = $product ? $product->price : 0;
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * ($item['quantity'] ?? 1),
                ]);
            }
        }

        // حفظ عناوين الطلب
        if($request->addresses){
            foreach($request->addresses as $addr){
                if(empty($addr['email'])) continue;
                $order->addresses()->create($addr);
            }
        }

        return redirect()->route('admin.orders.index')->with('success','تم حفظ الطلب بنجاح.');
    }

    // ============================
    // تعديل الطلب
    // ============================
    public function edit($id)
    {
        $order = Order::with(['items','addresses'])->findOrFail($id);
        $customers = Customer::with('user')->get();
        $products  = Product::all();
        return view('frontend.admin.dashboard.orders.form_order', compact('order','customers','products'));
    }

    // ============================
    // تحديث الطلب
    // ============================
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_number' => 'required|string|max:255',
            'subtotal' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'shipping_amount' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'payment_method' => 'nullable|string',
            'order_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $order->update($data);

        // تحديث عناصر الطلب
        $order->items()->delete();
        if($request->items){
            foreach($request->items as $item){
                if(empty($item['product_id'])) continue;
                $product = Product::find($item['product_id']);
                $unitPrice = $product ? $product->price : 0;
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * ($item['quantity'] ?? 1),
                ]);
            }
        }

        // تحديث عناوين الطلب
        $order->addresses()->delete();
        if($request->addresses){
            foreach($request->addresses as $addr){
                $order->addresses()->create($addr);
            }
        }

        return redirect()->route('admin.orders.index')->with('success','تم تحديث الطلب بنجاح.');
    }

    // ============================
    // حذف الطلب
    // ============================
    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->addresses()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success','تم حذف الطلب بنجاح.');
    }

    // ============================
    // عرض تفاصيل الطلب
    // ============================
    public function show(Order $order)
    {
        $order->load(['customer.user','items.product','items.variant','addresses']);
        return view('frontend.admin.dashboard.orders.order_details', compact('order'));
    }

    // ============================
    // عرض عناصر الطلبات
    // ============================

    public function itemsIndex()
    {
        $items = OrderItem::with(['order','product','variant'])
            ->orderBy('item_id','desc')
            ->paginate(15);

        $orders = Order::all();
        $products = Product::all();

        return view('frontend.admin.dashboard.orders.order_items_all', compact('items', 'orders', 'products'));
    }


    // ============================
    // حفظ عناصر الطلبات
    // ============================
    public function saveOrderItems(Request $request)
    {
        if($request->items){
            foreach($request->items as $item){
                if(empty($item['product_id'])) continue;

                $product = Product::find($item['product_id']); 
                $unitPrice = $product ? $product->price : ($item['unit_price'] ?? 0);

                if(!empty($item['item_id'])){
                    $orderItem = OrderItem::find($item['item_id']);
                    if(!$orderItem) continue;

                    $orderItem->update([
                        'order_id'    => $item['order_id'],
                        'product_id'  => $item['product_id'],
                        'variant_id'  => $item['variant_id'] ?? null,
                        'quantity'    => $item['quantity'] ?? 1,
                        'unit_price'  => $unitPrice,
                        'total_price' => $unitPrice * ($item['quantity'] ?? 1)
                    ]);

                } else {
                    OrderItem::create([
                        'order_id'    => $item['order_id'],
                        'product_id'  => $item['product_id'],
                        'variant_id'  => $item['variant_id'] ?? null,
                        'quantity'    => $item['quantity'] ?? 1,
                        'unit_price'  => $unitPrice,
                        'total_price' => $unitPrice * ($item['quantity'] ?? 1)
                    ]);
                }
            }
        }

        return redirect()->back()->with('success','تم حفظ عناصر الطلب بنجاح');
    }

    // ============================
    // حذف عنصر الطلب باستخدام AJAX
    // ============================
    public function deleteOrderItem(OrderItem $item)
    {
        $item->delete();
        return response()->json(['success'=>'تم حذف عنصر الطلب بنجاح']);
    }

    // ============================
    // عرض عناوين الطلبات
    // ============================
    public function addressesIndex()
    {
        $addresses = OrderAddress::with('order')->orderBy('order_address_id','desc')->paginate(15);
        return view('frontend.admin.dashboard.orders.order_addresses_all', compact('addresses'));
    }

    // ============================
    // حفظ عناوين الطلبات
    // ============================
    public function saveOrderAddresses(Request $request)
    {
        if($request->addresses){
            foreach($request->addresses as $addr){
                if(!empty($addr['order_address_id'])){
                    $address = OrderAddress::find($addr['order_address_id']);
                    if(!$address) continue;

                    $address->update($addr);
                } else {
                    OrderAddress::create($addr);
                }
            }
        }

        return redirect()->back()->with('success','تم حفظ عناوين الطلب بنجاح');
    }

    // ============================
    // حذف عنوان الطلب باستخدام AJAX
    // ============================
    public function deleteOrderAddress(OrderAddress $address)
    {
        $address->delete();
        return response()->json(['success'=>'تم حذف عنوان الطلب بنجاح']);
    }

    // ============================
    // عرض عناوين العملاء
    // ============================
    public function userAddressesIndex()
    {
        $addresses = Address::with('user')->orderBy('address_id','desc')->paginate(15);
        return view('frontend.admin.dashboard.orders.user_addresses_all', compact('addresses'));
    }

    // ============================
    // حفظ عناوين العملاء
    // ============================
    public function saveUserAddresses(Request $request)
    {
        if($request->addresses){
            foreach($request->addresses as $addr){
                if(!empty($addr['address_id'])){
                    $address = Address::find($addr['address_id']);
                    if(!$address) continue;
                    $address->update($addr);
                } else {
                    Address::create($addr);
                }
            }
        }

        return redirect()->back()->with('success','تم حفظ عناوين العملاء بنجاح');
    }

    // ============================
    // حذف عنوان العميل باستخدام AJAX
    // ============================
    public function deleteUserAddress(Address $address)
    {
        $address->delete();
        return response()->json(['success'=>'تم حذف عنوان العميل بنجاح']);
    }
}
