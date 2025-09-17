@extends('frontend.layouts.master')
@section('sidebar') 
    @include('frontend.Seller.partials.sidebar') 
@endsection
@section('content')

<section class="dashboard-area">
    @include('frontend.layouts.header')
    
    <div class="dashboard-content-wrap">
        <div class="dashboard-bread dashboard-bread-2">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="breadcrumb-content">
                            <div class="section-heading">
                                <h2 class="sec__title font-size-30 text-white">@yield('page_title', 'لوحة القيادة')</h2>
                            </div>

                        </div>
                        <!-- end breadcrumb-content -->
                    </div>
                    <!-- end col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="breadcrumb-list text-end">
                            <ul class="list-items">
                                <li><a href="{{ url('/') }}" class="text-white">الصفحة الرئيسية</a></li>
                                <li>لوحة التحكم</li>
                                <li>@yield('page_title', 'لوحة القيادة')</li>
                            </ul>
                        </div>
                        <!-- end breadcrumb-list -->
                    </div>
                    <!-- end col-lg-6 -->
                </div>
                <!-- end row -->
                
                <!-- إحصائيات سريعة -->
                @yield('statistics')
                <!-- end row -->
            </div>
        </div>
                <div class="dashboard-main-content">
            <div class="container-fluid">
@yield('contects')
               @include('frontend.layouts.footer')
            </div>
            <!-- end container-fluid -->
        </div>
    </div>
    <!-- end dashboard-content-wrap -->
</section>

@endsection