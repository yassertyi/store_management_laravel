<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('sort_order')->paginate(10);
        return view('frontend.admin.dashboard.categories.categorie', compact('categories'));
    }

    public function create()
    {
        $parents = Category::all();
        return view('frontend.admin.dashboard.categories.forms_categories', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,category_id',
            'image'       => 'nullable|image',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('static/images/categories', 'public');
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'تمت إضافة التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('category_id', '!=', $category->category_id)->get();
        return view('frontend.admin.dashboard.categories.forms_categories', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,category_id',
            'image'       => 'nullable|image',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('static/images/categories', 'public');
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف');
    }
}
