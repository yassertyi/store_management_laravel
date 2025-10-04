<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\FooterLink;
use App\Models\SocialMedia;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // مشاركة الإعدادات مع جميع الـ views
        View::composer('*', function ($view) {
            $settings = [
                'header_phone' => Setting::getValue('header_phone', '770997501'),
                'header_email' => Setting::getValue('header_email', 'yasser@gmail.com'),
                'footer_description' => Setting::getValue('footer_description', 'متجرنا يجمع بين عدة متاجر في اليمن'),
                'footer_address' => Setting::getValue('footer_address', 'صنعاء شارع الاصبحي الحربي سابقاً'),
                'footer_phone' => Setting::getValue('footer_phone', '+967 770 997 501'),
                'footer_email' => Setting::getValue('footer_email', 'yasser@gmail.com'),
                'copyright_text' => Setting::getValue('copyright_text', '&copy; 2024 متجرنا. جميع الحقوق محفوظة.'),
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

            // روابط الفوتر
            $storeLinks = FooterLink::active()->bySection('store')->ordered()->get();
            $customerServiceLinks = FooterLink::active()->bySection('customer_service')->ordered()->get();
            $informationLinks = FooterLink::active()->bySection('information')->ordered()->get();

            // وسائل التواصل الاجتماعي
            $socialMedia = SocialMedia::active()->ordered()->get();

            $view->with(compact('settings', 'storeLinks', 'customerServiceLinks', 'informationLinks', 'socialMedia'));
        });
    }
}