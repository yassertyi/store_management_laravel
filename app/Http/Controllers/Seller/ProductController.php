<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;

class ProductController extends Controller
{
    // عرض جميع المنتجات الخاصة بالبائع
    public function index()
    {
        
        $storeId = Auth::user()->seller->store_id;

        $products = Product::with(['images','variants'])
                    ->where('store_id', $storeId)
                    ->orderBy('product_id','desc')
                    ->paginate(15);

        return view('frontend.Seller.dashboard.products.products', compact('products'));
    }

    // صفحة إنشاء منتج جديد
    public function create()
    {
        $categories = Category::all();
        return view('frontend.Seller.dashboard.products.forms_product', compact('categories'));
    }

    // دالة توليد SKU فريد
    private function generateUniqueSku()
    {
        do {
            $sku = 'SKU' . mt_rand(1000000, 9999999); 
        } while (Product::where('sku', $sku)->exists() || ProductVariant::where('sku', $sku)->exists());
        return $sku;
    }

    // حفظ منتج جديد
    public function store(Request $request)
    {
        $storeId = Auth::user()->seller->store_id;

        $data = $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'stock' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'nullable|boolean'
        ]);

        $data['store_id'] = $storeId;

        if(empty($data['sku']) || Product::where('sku', $data['sku'])->exists()) {
            $data['sku'] = $this->generateUniqueSku();
        }

        $product = Product::create($data);

        // حفظ الصور والمتغيرات
        $this->saveImages($request, $product);
        $this->saveVariants($request, $product);

        return redirect()->route('seller.products.index')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    // صفحة تعديل المنتج
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $product->load(['images','variants']);
        $categories = Category::all();
        return view('frontend.Seller.dashboard.products.forms_product', compact('product','categories'));
    }

    // تحديث المنتج
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'stock' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'nullable|boolean'
        ]);

        if(empty($data['sku']) || Product::where('sku', $data['sku'])->where('product_id', '!=', $product->product_id)->exists()) {
            $data['sku'] = $this->generateUniqueSku();
        }

        $product->update($data);

        // حفظ الصور والمتغيرات
        $this->saveImages($request, $product);
        $this->saveVariants($request, $product);

        return redirect()->route('seller.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    // حذف المنتج
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        foreach($product->images as $img){
            if(file_exists(public_path($img->image_path))){
                unlink(public_path($img->image_path));
            }
        }
        $product->delete();
        return redirect()->route('seller.products.index')->with('success','تم حذف المنتج بنجاح');
    }

    // عرض تفاصيل المنتج
    public function show(Product $product)
    {
        $product->load(['store', 'category', 'images', 'variants']);
        return view('frontend.Seller.dashboard.products.product_details', compact('product'));
    }

    // التحقق من صلاحية المنتج
    private function authorizeProduct(Product $product)
    {
        if($product->store_id != Auth::user()->seller->store_id){
            abort(403, 'ليس لديك صلاحية للوصول لهذا المنتج.');
        }
    }

    // =========================
    // إدارة الصور والمتغيرات
    // =========================

    // عرض الصور
    public function imagesIndex()
    {
        $storeId = Auth::user()->seller->store_id;

        $images = ProductImage::whereHas('product', function($q) use ($storeId){
            $q->where('store_id', $storeId);
        })->paginate(15);

        $products = Product::where('store_id', $storeId)->get();

        return view('frontend.Seller.dashboard.products.product_images', compact('images','products'));
    }

    public function saveImages(Request $request, Product $product = null)
{
    $submittedImages = [];

    if($request->images){
        foreach ($request->images as $img){

            // الاحتفاظ بالمسار الحالي للصورة إذا لم يتم تحميل صورة جديدة
            $imagePath = $img['image_path'] ?? null;

            if(!empty($img['image'])){
                $imageFile = $img['image'];
                $imageName = time().'_'.rand(1,999).'.'.$imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('static/images/products'), $imageName);
                $imagePath = 'static/images/products/'.$imageName;
            }

            $image = ProductImage::updateOrCreate(
                ['image_id' => $img['image_id'] ?? null],
                [
                    'product_id' => $img['product_id'],
                    'image_path' => $imagePath, // الاحتفاظ بالمسار القديم إذا لم يتم تغيير الصورة
                    'alt_text' => $img['alt_text'] ?? '',       // النص البديل
                    'sort_order' => $img['sort_order'] ?? 0,    // الترتيب
                    'is_primary' => isset($img['is_primary']) ? $img['is_primary'] : 0 // الأساسي
                ]
            );

            $submittedImages[] = $image->image_id;
        }
    }

    if($product){
        ProductImage::where('product_id', $product->product_id)
                    ->whereNotIn('image_id', $submittedImages)
                    ->delete();
    }

    return redirect()->back()->with('success','تم حفظ الصور بنجاح');
}


    // عرض المتغيرات
   public function variantsIndex()
{
    $storeId = Auth::user()->seller->store_id;

    $variants = ProductVariant::whereHas('product', function($q) use ($storeId){
        $q->where('store_id', $storeId);
    })->paginate(15); 

    $products = Product::where('store_id', $storeId)->get(); 
    return view('frontend.Seller.dashboard.products.product_variants', compact('variants','products'));
}


    // حفظ المتغيرات (public ليتم استدعاؤها من Route)
    public function saveVariants(Request $request)
{
    $submittedVariants = [];

    if ($request->variants) {
        foreach($request->variants as $var) {
            if(empty($var['name']) || empty($var['product_id'])) continue; // تخطي إذا ناقص

            // إذا كان SKU فارغ أو موجود مسبقًا لمنتج آخر، يولد تلقائي
            $sku = $var['sku'] ?? null;
            if(empty($sku) || ProductVariant::where('sku', $sku)
                    ->where('variant_id', '!=', $var['variant_id'] ?? 0)
                    ->exists()) {
                $sku = $this->generateUniqueSku();
            }

            $variant = ProductVariant::updateOrCreate(
                ['variant_id' => $var['variant_id'] ?? null], // يستخدم variant_id إذا موجود
                [
                    'product_id' => $var['product_id'],
                    'name' => $var['name'],
                    'price' => $var['price'] ?? 0,
                    'stock' => $var['stock'] ?? 0,
                    'sku' => $sku
                ]
            );

            $submittedVariants[$var['product_id']][] = $variant->variant_id;
        }

        // حذف المتغيرات الغير موجودة لكل منتج
        foreach ($submittedVariants as $productId => $variantIds) {
            ProductVariant::where('product_id', $productId)
                          ->whereNotIn('variant_id', $variantIds)
                          ->delete();
        }
    }

    return redirect()->back()->with('success','تم حفظ المتغيرات بنجاح');
}


    // حذف صورة باستخدام AJAX
    public function deleteImage(ProductImage $image)
    {
        $this->authorizeProduct($image->product);

        if($image->image_path && file_exists(public_path($image->image_path))){
            unlink(public_path($image->image_path));
        }
        $image->delete();
        return response()->json(['success' => 'تم حذف الصورة بنجاح']);
    }

    // حذف متغير باستخدام AJAX
    public function deleteVariant(ProductVariant $variant)
    {
        $this->authorizeProduct($variant->product);

        $variant->delete();
        return response()->json(['success'=>'تم حذف المتغير بنجاح']);
    }
}
