@extends('frontend.home.layouts.master')

@section('title', 'الصفحة الرئيسية')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 <link rel="stylesheet" href="{{ asset('static/css/home.css') }}" />

@endsection
@section('content')
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.info')
    @include('frontend.home.sections.categories')
    @include('frontend.home.sections.products')
    @include('frontend.home.sections.offers')
    @include('frontend.home.sections.featured-products')
    @include('frontend.home.sections.testimonials')
    @include('frontend.home.sections.brands')
    @include('frontend.home.sections.newsletter')
@endsection

