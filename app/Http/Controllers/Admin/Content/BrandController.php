<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::ordered()->get();
        return view('frontend.admin.content.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('frontend.admin.content.brands.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // تغيير إلى image
            'website' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        // رفع صورة الشعار
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);

        return redirect()->route('admin.content.brands.index')->with('success', 'تم إضافة العلامة التجارية بنجاح');
    }

    public function edit(Brand $brand)
    {
        return view('frontend.admin.content.brands.form', compact('brand'));
    }

   public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // تغيير إلى image
            'website' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        // رفع صورة الشعار
        if ($request->hasFile('logo')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        return redirect()->route('admin.content.brands.index')->with('success', 'تم تحديث العلامة التجارية بنجاح');
    }

   public function destroy(Brand $brand)
    {
        // حذف الصورة إذا كانت موجودة
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }
        
        $brand->delete();
        return redirect()->route('admin.content.brands.index')->with('success', 'تم حذف العلامة التجارية بنجاح');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->update(['is_active' => !$brand->is_active]);
        return redirect()->back()->with('success', 'تم تغيير الحالة بنجاح');
    }
}