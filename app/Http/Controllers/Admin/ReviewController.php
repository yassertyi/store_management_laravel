<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function index(Request $request)
{
    $query = Review::with(['product', 'user', 'order']);

    // فلترة حسب التاريخ
    if ($request->filter == 'latest') {
        $query->orderBy('created_at', 'desc');
    } elseif ($request->filter == 'oldest') {
        $query->orderBy('created_at', 'asc');
    }

    $reviews = $query->paginate(10);

    return view('frontend.admin.dashboard.reviews.reviews', compact('reviews'));
}


    public function create()
    {
        $products = Product::all();
        $users = User::all();
        $orders = Order::all();
        return view('frontend.admin.dashboard.reviews.forms_reviews', compact('products', 'users', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id'    => 'required|exists:users,user_id',
            'order_id'   => 'nullable|exists:orders,order_id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'nullable|string|max:255',
            'comment'    => 'nullable|string',
        ]);

        Review::create([
            'product_id'  => $request->product_id,
            'user_id'     => $request->user_id,
            'order_id'    => $request->order_id ?? null,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'comment'     => $request->comment,
            'is_approved' => $request->has('is_approved') ? 1 : 0,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'تم إضافة التقييم بنجاح');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $products = Product::all();
        $users = User::all();
        $orders = Order::all();
        return view('frontend.admin.dashboard.reviews.forms_reviews', compact('review', 'products', 'users', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id'    => 'required|exists:users,user_id',
            'order_id'   => 'nullable|exists:orders,order_id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'nullable|string|max:255',
            'comment'    => 'nullable|string',
        ]);

        $review->update([
            'product_id'  => $request->product_id,
            'user_id'     => $request->user_id,
            'order_id'    => $request->order_id ?? null,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'comment'     => $request->comment,
            'is_approved' => $request->has('is_approved') ? 1 : 0,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'تم تعديل التقييم بنجاح');
    }

    public function destroy($id)
    {
        Review::destroy($id);
        return redirect()->route('admin.reviews.index')->with('success', 'تم حذف التقييم بنجاح');
    }
}
