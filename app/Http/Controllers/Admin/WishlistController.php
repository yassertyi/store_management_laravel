<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with(['product', 'user'])->paginate(10);
        return view('frontend.admin.dashboard.reviews.wishlists', compact('wishlists'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('frontend.admin.dashboard.reviews.forms_wishlists', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id'    => 'required|exists:users,user_id',
        ]);

        Wishlist::create($request->all());

        return redirect()->route('admin.wishlists.index')->with('success', 'تم إضافة المنتج إلى المفضلة');
    }


    public function edit($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $products = Product::all();
        $users = User::all();
        return view('frontend.admin.dashboard.reviews.forms_wishlists', compact('wishlist', 'products', 'users'));
    }

    public function update(Request $request, $id)
    {
        $wishlist = Wishlist::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id'    => 'required|exists:users,user_id',
        ]);

        $wishlist->update($request->all());

        return redirect()->route('admin.wishlists.index')->with('success', 'تم تعديل المفضلة بنجاح');
    }

    public function destroy($id)
    {
        Wishlist::destroy($id);
        return redirect()->route('admin.wishlists.index')->with('success', 'تم حذف المفضلة');
    }
}
