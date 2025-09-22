<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ReviewHelpful;
use Illuminate\Support\Facades\Auth;

class ReviewHelpfulController extends Controller
{
    public function index()
    {
        $store = Auth::user()->seller->store_id;

        $helpfuls = ReviewHelpful::whereHas('review.product', function ($q) use ($store) {
            $q->where('store_id', $store);
        })->latest()->paginate(10);

        return view('frontend.Seller.dashboard.reviews.review_helpful', compact('helpfuls'));
    }
}
