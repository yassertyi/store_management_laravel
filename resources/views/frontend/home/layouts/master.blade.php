<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="TechyDevs" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'متجرنا - المتجر الإلكتروني الرائد')</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('static/images/Newfolder/aa.png') }}" />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
      rel="stylesheet"
    />

    <!-- Template CSS Files -->
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap-rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/line-awesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/jquery.fancybox.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/animated-headline.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/jquery.filer.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/flag-icon.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/style-rtl.css') }}" />
        <link rel="stylesheet" href="{{ asset('static/css/home.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/prodect.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('style')
</head>
<body>
    <!-- start cssload-loader -->
    <div class="preloader" id="preloader">
      <div class="loader">
        <svg class="spinner" viewBox="0 0 50 50">
          <circle
            class="path"
            cx="25"
            cy="25"
            r="20"
            fill="none"
            stroke-width="5"
          ></circle>
        </svg>
      </div>
    </div>
    <!-- end cssload-loader -->

    @include('frontend.home.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('frontend.home.layouts.footer')


    <!-- start back-to-top -->
    <div id="back-to-top">
      <i class="la la-angle-up" title="Go top"></i>
    </div>
    <!-- end back-to-top -->

    <!-- Template JS Files -->
    <script src="{{ asset('static/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('static/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('static/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('static/js/select2.min.js') }}"></script>
    <script src="{{ asset('static/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('static/js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('static/js/jquery.countTo.min.js') }}"></script>
    <script src="{{ asset('static/js/animated-headline.js') }}"></script>
    <script src="{{ asset('static/js/jquery.filer.min.js') }}"></script>
    <script src="{{ asset('static/js/quantity-input.js') }}"></script>
    <script src="{{ asset('static/js/main-rtl.js') }}"></script>
    
    @yield('scripts')
</body>
</html>