<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // الحصول على عناصر السلة مجمعة حسب المتجر
        $cartItemsByStore = $this->getCartItemsGroupedByStore();
        $totalCartCount = $this->getCartCount();
        $grandTotal = $this->getGrandTotal();

        return view('frontend.home.sections.cart', compact(
            'cartItemsByStore', 
            'totalCartCount', 
            'grandTotal'
        ));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,variant_id'
        ]);

        // إذا كان الطلب AJAX، أعد JSON response
        if ($request->ajax() || $request->wantsJson()) {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً',
                    'login_required' => true
                ], 401);
            }

            try {
                $product = Product::with('store')->findOrFail($request->product_id);
                
                // تحديد السعر والمخزون بناءً على المتغير
                $price = $product->price;
                $stock = $product->stock;
                $variant = null;
                
                if ($request->variant_id) {
                    $variant = ProductVariant::find($request->variant_id);
                    if ($variant) {
                        $price = $variant->price;
                        $stock = $variant->stock;
                    }
                }

                // التحقق من المخزون
                if ($stock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الكمية المطلوبة غير متوفرة في المخزون. المتاح: ' . $stock
                    ], 400);
                }

                // البحث عن العنصر الموجود في السلة لنفس المتجر والمنتج والمتغير
                $existingCartItem = CartItem::where('user_id', Auth::id())
                    ->where('store_id', $product->store_id)
                    ->where('product_id', $request->product_id)
                    ->where('variant_id', $request->variant_id)
                    ->first();

                if ($existingCartItem) {
                    // التحقق من المخزون الإجمالي
                    $totalQuantity = $existingCartItem->quantity + $request->quantity;
                    if ($stock < $totalQuantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'الكمية الإجمالية تتجاوز المخزون المتاح. المتاح: ' . $stock
                        ], 400);
                    }
                    
                    $existingCartItem->quantity = $totalQuantity;
                    $existingCartItem->save();
                } else {
                    // إضافة عنصر جديد إلى السلة
                    CartItem::create([
                        'user_id' => Auth::id(),
                        'store_id' => $product->store_id,
                        'product_id' => $request->product_id,
                        'variant_id' => $request->variant_id,
                        'quantity' => $request->quantity,
                        'price' => $price
                    ]);
                }

                $cartCount = $this->getCartCount();
                $storesCount = $this->getStoresCount();

                return response()->json([
                    'success' => true,
                    'message' => 'تمت إضافة المنتج إلى السلة بنجاح',
                    'cart_count' => $cartCount,
                    'stores_count' => $storesCount,
                    'store_id' => $product->store_id
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إضافة المنتج إلى السلة: ' . $e->getMessage()
                ], 500);
            }
        }

        // إذا كان طلب عادي (غير AJAX) - إضافة المنتج والعودة لنفس الصفحة
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        try {
            $product = Product::with('store')->findOrFail($request->product_id);
            
            // تحديد السعر والمخزون بناءً على المتغير
            $price = $product->price;
            $stock = $product->stock;
            $variant = null;
            
            if ($request->variant_id) {
                $variant = ProductVariant::find($request->variant_id);
                if ($variant) {
                    $price = $variant->price;
                    $stock = $variant->stock;
                }
            }

            // التحقق من المخزون
            if ($stock < $request->quantity) {
                return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون. المتاح: ' . $stock);
            }

            // البحث عن العنصر الموجود في السلة لنفس المتجر والمنتج والمتغير
            $existingCartItem = CartItem::where('user_id', Auth::id())
                ->where('store_id', $product->store_id)
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();

            if ($existingCartItem) {
                // التحقق من المخزون الإجمالي
                $totalQuantity = $existingCartItem->quantity + $request->quantity;
                if ($stock < $totalQuantity) {
                    return back()->with('error', 'الكمية الإجمالية تتجاوز المخزون المتاح. المتاح: ' . $stock);
                }
                
                $existingCartItem->quantity = $totalQuantity;
                $existingCartItem->save();
            } else {
                // إضافة عنصر جديد إلى السلة
                CartItem::create([
                    'user_id' => Auth::id(),
                    'store_id' => $product->store_id,
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                    'price' => $price
                ]);
            }

            // العودة لنفس الصفحة مع رسالة نجاح
            return back()->with('success', 'تمت إضافة المنتج إلى السلة بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إضافة المنتج إلى السلة: ' . $e->getMessage());
        }
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $cartItem = CartItem::with(['product', 'variant'])
                ->where('user_id', Auth::id())
                ->where('cart_item_id', $request->cart_item_id)
                ->firstOrFail();

            // التحقق من المخزون
            $stock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock;
            if ($stock < $request->quantity) {
                return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون. المتاح: ' . $stock);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return redirect()->route('front.cart.index')->with('success', 'تم تحديث الكمية بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الكمية');
        }
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id'
        ]);

        try {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('cart_item_id', $request->cart_item_id)
                ->first();

            if (!$cartItem) {
                return back()->with('error', 'العنصر غير موجود في السلة');
            }

            $cartItem->delete();

            return redirect()->route('front.cart.index')->with('success', 'تم إزالة المنتج من السلة');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إزالة المنتج');
        }
    }

    public function clearCart()
    {
        try {
            CartItem::where('user_id', Auth::id())->delete();
            return redirect()->route('front.cart.index')->with('success', 'تم تفريغ السلة بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تفريغ السلة');
        }
    }

    public function clearStoreCart($storeId)
    {
        try {
            CartItem::where('user_id', Auth::id())
                ->where('store_id', $storeId)
                ->delete();

            return redirect()->route('front.cart.index')->with('success', 'تم تفريغ منتجات المتجر من السلة');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف منتجات المتجر');
        }
    }

    public function getCartCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }

    public function getCartSummary()
    {
        if (!Auth::check()) {
            return response()->json(['cart_count' => 0, 'stores_count' => 0]);
        }

        return response()->json([
            'cart_count' => $this->getCartCount(),
            'stores_count' => $this->getStoresCount(),
            'grand_total' => number_format($this->getGrandTotal(), 2)
        ]);
    }

    // الدوال المساعدة
    private function getCartItemsGroupedByStore()
    {
        return CartItem::with(['store', 'product.images', 'variant'])
            ->where('user_id', Auth::id())
            ->get()
            ->groupBy('store_id');
    }

    private function getStoresCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        return CartItem::where('user_id', Auth::id())
            ->distinct('store_id')
            ->count('store_id');
    }

    private function getStoreTotal($storeId)
    {
        return CartItem::where('user_id', Auth::id())
            ->where('store_id', $storeId)
            ->get()
            ->sum(function($item) {
                return $item->price * $item->quantity;
            });
    }

    private function getGrandTotal()
    {
        return CartItem::where('user_id', Auth::id())
            ->get()
            ->sum(function($item) {
                return $item->price * $item->quantity;
            });
    }
}