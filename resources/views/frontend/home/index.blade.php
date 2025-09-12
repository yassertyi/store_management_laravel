@extends('frontend.home.layouts.master')

@section('title', 'الصفحة الرئيسية')

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