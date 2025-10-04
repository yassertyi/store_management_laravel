@extends('frontend.home.layouts.master')

@section('title', 'الصفحة الرئيسية')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('static/css/home.css') }}" />
<style>
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection

@section('content')

    <!-- الأقسام -->
    <section id="hero">
        @include('frontend.home.sections.hero')
    </section>

    <section id="info">
        @include('frontend.home.sections.info')
    </section>

    <section id="categories">
        @include('frontend.home.sections.categories')
    </section>

    <section id="products">
        @include('frontend.home.sections.products')
    </section>

    <section id="stores">
        @include('frontend.home.sections.stores')
    </section>

    <section id="offers">
        @include('frontend.home.sections.offers')
    </section>

    <section id="featured-products">
        @include('frontend.home.sections.featured-products')
    </section>

    <section id="testimonials">
        @include('frontend.home.sections.testimonials')
    </section>

    <section id="brands">
        @include('frontend.home.sections.brands')
    </section>

    <section id="newsletter">
        @include('frontend.home.sections.newsletter')
    </section>

@endsection
