<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class SellerReviewController extends Controller
{
    public function index()
    {
        $store = Auth::user()->seller->store_id;

        $reviews = Review::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store);
        })->latest()->paginate(10);

        return view('frontend.Seller.dashboard.reviews.reviews', compact('reviews'));
    }

   
}
