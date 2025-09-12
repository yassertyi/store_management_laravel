<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewHelpful;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewHelpfulController extends Controller
{
    public function index()
    {
        $helpfuls = ReviewHelpful::with(['review', 'user'])->paginate(10);
        return view('frontend.admin.dashboard.reviews.review_helpful', compact('helpfuls'));
    }

    public function create()
    {
        $reviews = Review::all();
        $users = User::all();
        return view('frontend.admin.dashboard.reviews.forms_review_helpful', compact('reviews', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'review_id'  => 'required|exists:reviews,review_id',
            'user_id'    => 'required|exists:users,user_id',
            'is_helpful' => 'nullable|boolean',
        ]);

        ReviewHelpful::create([
            'review_id'  => $request->review_id,
            'user_id'    => $request->user_id,
            'is_helpful' => $request->has('is_helpful') ? 1 : 0,
        ]);

        return redirect()->route('admin.review-helpful.index')->with('success', 'تم إضافة التصويت بنجاح');
    }

    public function edit($id)
    {
        $helpful = ReviewHelpful::findOrFail($id);
        $reviews = Review::all();
        $users = User::all();
        return view('frontend.admin.dashboard.reviews.forms_review_helpful', compact('helpful', 'reviews', 'users'));
    }

    public function update(Request $request, $id)
    {
        $helpful = ReviewHelpful::findOrFail($id);

        $request->validate([
            'is_helpful' => 'nullable|boolean',
        ]);

        $helpful->update([
            'review_id'  => $request->review_id,
            'user_id'    => $request->user_id,
            'is_helpful' => $request->has('is_helpful') ? 1 : 0,
        ]);

        return redirect()->route('admin.review-helpful.index')->with('success', 'تم تعديل التصويت بنجاح');
    }

    public function destroy($id)
    {
        ReviewHelpful::destroy($id);
        return redirect()->route('admin.review-helpful.index')->with('success', 'تم حذف التصويت بنجاح');
    }
}
