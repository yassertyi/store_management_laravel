<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\FooterLink;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // الفئات مع عدد المنتجات
        $categories = Category::withCount('products')->get();

        // المنتجات المميزة فقط + تحميل العلاقات (store, category, images, reviews)
        $featuredProducts = Product::with(['category', 'store', 'images', 'reviews'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->take(8)
            ->get();

        $allProducts = Product::with(['category', 'store', 'images', 'reviews'])
            ->where('status', 'active')
            ->take(12)
            ->get();

        // جميع المتاجر للفلترة
        $stores = Store::withCount(['products' => function($query) {
            $query->where('status', 'active');
        }])->get();

        // المتاجر المميزة للعرض في القسم الجديد
        $featuredStores = Store::with(['user', 'products', 'addresses'])
            ->where('status', 'active')
            ->whereHas('products', function($query) {
                $query->where('status', 'active');
            })
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // جلب البيانات الديناميكية الجديدة
        $brands = Brand::active()->ordered()->get();
        $testimonials = Testimonial::active()->ordered()->get();
        $socialMedia = SocialMedia::active()->ordered()->get();
        
        // جلب الإعدادات
        $settings = [
            'header_phone' => Setting::getValue('header_phone', '770997501'),
            'header_email' => Setting::getValue('header_email', 'yasser@gmail.com'),
            'footer_description' => Setting::getValue('footer_description', 'متجرنا يجمع بين عدة متاجر في اليمن'),
            'footer_address' => Setting::getValue('footer_address', 'صنعاء شارع الاصبحي الحربي سابقاً'),
            'footer_phone' => Setting::getValue('footer_phone', '+967 770 997 501'),
            'footer_email' => Setting::getValue('footer_email', 'yasser@gmail.com'),
            'copyright_text' => Setting::getValue('copyright_text', '&yasser@gmail.com; 2024 متجرنا. جميع الحقوق محفوظة.'),
            'brands_title' => Setting::getValue('brands_title', 'العلامات التجارية التي نتعامل معها'),
            'newsletter_title' => Setting::getValue('newsletter_title', 'اشترك في النشرة الإخبارية'),
            'newsletter_description' => Setting::getValue('newsletter_description', 'كن أول من يعرف عن العروض الخاصة والمنتجات الجديدة'),
            'testimonials_title' => Setting::getValue('testimonials_title', 'آراء عملائنا'),
            'shipping_title' => Setting::getValue('shipping_title', 'شحن مجاني'),
            'shipping_description' => Setting::getValue('shipping_description', 'شحن مجاني لجميع الطلبات فوق 20000 ريال داخل صنعاء'),
            'return_title' => Setting::getValue('return_title', 'إرجاع مجاني'),
            'return_description' => Setting::getValue('return_description', 'إرجاع المنتجات خلال 14 يوم من الاستلام بدون أي رسوم'),
            'payment_title' => Setting::getValue('payment_title', 'دفع آمن'),
            'payment_description' => Setting::getValue('payment_description', 'أنظمة دفع آمنة ومشفرة لحماية معلوماتك الشخصية'),
        ];

        // جلب روابط الفوتر
        $storeLinks = FooterLink::active()->bySection('store')->ordered()->get();
        $customerServiceLinks = FooterLink::active()->bySection('customer_service')->ordered()->get();
        $informationLinks = FooterLink::active()->bySection('information')->ordered()->get();

        return view('frontend.home.index', compact(
            'categories', 
            'featuredProducts', 
            'allProducts',
            'stores',
            'featuredStores',
            'brands',
            'testimonials',
            'socialMedia',
            'settings',
            'storeLinks',
            'customerServiceLinks',
            'informationLinks'
        ));
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email'
        ]);

        // هنا يمكنك إضافة كود حفظ الإيميل في قاعدة البيانات
        // NewsletterSubscription::create(['email' => $request->email]);

        return redirect()->back()->with('success', 'تم الاشتراك في النشرة البريدية بنجاح!');
    }
}