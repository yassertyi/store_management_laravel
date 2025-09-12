<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض جميع المنتجات
    public function index()
    {
        $products = Product::with(['store','images','variants'])
                    ->orderBy('product_id','desc')
                    ->paginate(15);
        return view('frontend.admin.dashboard.products.products_all', compact('products'));
    }

    // صفحة إنشاء منتج
    public function create()
    {
        $stores = Store::all();
        $categories = Category::all();
        return view('frontend.admin.dashboard.products.forms_product', compact('stores','categories'));
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
        $data = $request->validate([
            'store_id' => 'required|exists:stores,store_id',
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
            'status' => 'required|in:active,inactive,suspended',
            'is_featured' => 'nullable|boolean'
        ]);

        if(empty($data['sku']) || Product::where('sku', $data['sku'])->exists()) {
            $data['sku'] = $this->generateUniqueSku();
        }

        $product = Product::create($data);

        // حفظ الصور
        if ($request->images) {
            foreach ($request->images as $img) {
                if (!empty($img['image_path'])) {
                    $imageFile = $img['image_path'];
                    $imageName = time() . '_' . rand(1,999) . '.' . $imageFile->getClientOriginalExtension();
                    $imageFile->move(public_path('static/images/products'), $imageName);
                    ProductImage::create([
                        'product_id' => $product->product_id,
                        'image_path' => 'static/images/products/'.$imageName,
                        'is_primary' => $img['is_primary'] ?? 0
                    ]);
                }
            }
        }

        // حفظ المتغيرات
        if ($request->variants) {
            foreach ($request->variants as $var) {
                if (!empty($var['name'])) {
                    if(empty($var['sku']) || ProductVariant::where('sku', $var['sku'])->exists()) {
                        $var['sku'] = $this->generateUniqueSku();
                    }
                    ProductVariant::create([
                        'product_id' => $product->product_id,
                        'name' => $var['name'],
                        'price' => $var['price'] ?? $product->price,
                        'stock' => $var['stock'] ?? 0,
                        'sku' => $var['sku']
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    // صفحة تعديل المنتج
    public function edit(Product $product)
    {
        $product->load(['images','variants']);
        $stores = Store::all();
        $categories = Category::all();
        return view('frontend.admin.dashboard.products.forms_product', compact('product','stores','categories'));
    }

    // تحديث المنتج
public function update(Request $request, Product $product)
{
    $data = $request->validate([
        'store_id' => 'required|exists:stores,store_id',
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
        'status' => 'required|in:active,inactive,suspended',
        'is_featured' => 'nullable|boolean'
    ]);

    if(empty($data['sku']) || Product::where('sku', $data['sku'])->where('product_id', '!=', $product->product_id)->exists()) {
        $data['sku'] = $this->generateUniqueSku();
    }

    $product->update($data);

    // ============================
    // تحديث الصور
    // ============================
    $submittedImages = [];

    if ($request->images) {
        foreach ($request->images as $index => $img) {

            // إذا تم رفع صورة جديدة
            if (!empty($img['image_path'])) {
                $imageFile = $img['image_path'];
                $imageName = time() . '_' . rand(1, 999) . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('static/images/products'), $imageName);

                $productImage = ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => 'static/images/products/' . $imageName,
                    'is_primary' => $img['is_primary'] ?? 0
                ]);

                $submittedImages[] = $productImage->image_id;

            // إذا كانت الصورة موجودة بالفعل ولم يتم رفع صورة جديدة
            } elseif (isset($img['image_id']) && $img['image_id']) {
                $submittedImages[] = $img['image_id'];
            }
        }
    }

    // حذف الصور القديمة غير المستخدمة
    ProductImage::where('product_id', $product->product_id)
                ->whereNotIn('image_id', $submittedImages)
                ->delete();

    // ============================
    // تحديث المتغيرات
    // ============================
    $submittedVariants = [];
    if ($request->variants) {
        foreach ($request->variants as $var) {
            if (!empty($var['name'])) {
                $productVariant = ProductVariant::updateOrCreate(
                    ['product_id' => $product->product_id, 'name' => $var['name']],
                    [
                        'price' => $var['price'] ?? $product->price,
                        'stock' => $var['stock'] ?? 0,
                        'sku' => (empty($var['sku']) || ProductVariant::where('sku', $var['sku'])
                                ->where('variant_id', '!=', $var['variant_id'] ?? 0)
                                ->exists())
                                ? $this->generateUniqueSku() : $var['sku']
                    ]
                );
                $submittedVariants[] = $productVariant->variant_id;
            }
        }

        // حذف المتغيرات القديمة غير المستخدمة
        ProductVariant::where('product_id', $product->product_id)
                      ->whereNotIn('variant_id', $submittedVariants)
                      ->delete();
    }

    return redirect($request->input('redirect_to', route('admin.products.index')))
           ->with('success', 'تم تحديث المنتج بنجاح');
}


    // حذف المنتج
    public function destroy(Product $product)
    {
        // حذف الصور من المجلد قبل الحذف
        foreach($product->images as $img){
            if(file_exists(public_path($img->image_path))){
                unlink(public_path($img->image_path));
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','تم حذف المنتج بنجاح');
    }

    // عرض تفاصيل المنتج
    public function show(Product $product)
    {
        $product->load(['store', 'category', 'images', 'variants']);
        return view('frontend.admin.dashboard.products.product_details', compact('product'));
    }



// صفحة عرض جميع الصور
public function imagesIndex()
{
    $images = ProductImage::with('product')->orderBy('image_id', 'desc')->paginate(15);
    $products = Product::all();
    return view('frontend.admin.dashboard.products.product_images_all', compact('images','products'));
}


// حفظ الصور (إضافة أو تعديل)
public function saveImages(Request $request)
{
    $savePath = public_path('static/images/products');

    // إنشاء المجلد إذا لم يكن موجود
    if (!file_exists($savePath)) {
        mkdir($savePath, 0777, true);
    }

    if($request->images){
        foreach($request->images as $img){
            if(!empty($img['image_id'])) {
                // تعديل الصورة القديمة
                $imageRecord = ProductImage::find($img['image_id']);
                if(isset($img['image'])) {
                    $imageFile = $img['image'];
                    $imageName = time() . '_' . rand(1,999) . '.' . $imageFile->getClientOriginalExtension();
                    $imageFile->move($savePath, $imageName);
                    $imageRecord->image_path = 'static/images/products/'.$imageName;
                }
                $imageRecord->alt_text = $img['alt_text'] ?? '';
                $imageRecord->sort_order = $img['sort_order'] ?? 0;
                $imageRecord->is_primary = $img['is_primary'] ?? 0;
                $imageRecord->product_id = $img['product_id'];
                $imageRecord->save();
            } elseif(isset($img['image'])) {
                // إضافة صورة جديدة فقط إذا تم رفع صورة
                $imageFile = $img['image'];
                $imageName = time() . '_' . rand(1,999) . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move($savePath, $imageName);

                ProductImage::create([
                    'product_id' => $img['product_id'],
                    'image_path' => 'static/images/products/'.$imageName,
                    'alt_text' => $img['alt_text'] ?? '',
                    'sort_order' => $img['sort_order'] ?? 0,
                    'is_primary' => $img['is_primary'] ?? 0,
                ]);
            }
        }
    }

    return redirect()->back()->with('success', 'تم حفظ التغييرات بنجاح');
}


// حذف صورة باستخدام AJAX
public function deleteImage(ProductImage $image)
{
    if($image->image_path && file_exists(public_path($image->image_path))){
        unlink(public_path($image->image_path));
    }
    $image->delete();
    return response()->json(['success' => 'تم حذف الصورة بنجاح']);
}


public function variantsIndex()
{
    $variants = ProductVariant::with('product')->orderBy('variant_id', 'desc')->paginate(15);
    $products = Product::all();
    return view('frontend.admin.dashboard.products.product_variants_all', compact('variants','products'));
}

// حفظ المتغيرات (إضافة أو تعديل)
public function saveVariants(Request $request)
{
    if($request->variants){
        foreach($request->variants as $key => $var){
            if(empty($var['name'])) continue;

            // تعديل متغير موجود
            if(!empty($var['variant_id'])){
                $variant = ProductVariant::find($var['variant_id']);
                if(!$variant) continue;

                $variant->product_id = $var['product_id'];
                $variant->name = $var['name'];
                $variant->price = $var['price'] ?? 0;
                $variant->stock = $var['stock'] ?? 0;

                if(empty($var['sku']) || ProductVariant::where('sku', $var['sku'])
                        ->where('variant_id','!=',$variant->variant_id)
                        ->exists()){
                    $variant->sku = $this->generateUniqueSku();
                } else {
                    $variant->sku = $var['sku'];
                }
                $variant->save();
            } else {
                // إضافة متغير جديد
                $sku = (empty($var['sku']) || ProductVariant::where('sku', $var['sku'])->exists())
                        ? $this->generateUniqueSku() : $var['sku'];

                ProductVariant::create([
                    'product_id' => $var['product_id'],
                    'name' => $var['name'],
                    'price' => $var['price'] ?? 0,
                    'stock' => $var['stock'] ?? 0,
                    'sku' => $sku
                ]);
            }
        }
    }

    return redirect()->back()->with('success','تم حفظ التغييرات بنجاح');
}

// حذف متغير باستخدام AJAX
public function deleteVariant(ProductVariant $variant)
{
    $variant->delete();
    return response()->json(['success'=>'تم حذف المتغير بنجاح']);
}
}