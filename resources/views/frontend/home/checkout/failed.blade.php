@extends('frontend.home.layouts.master')

@section('title', 'فشل في عملية الشراء')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
.checkout-failed-page {
    padding: 4rem 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.failed-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.failed-icon {
    font-size: 4rem;
    color: #dc3545;
    margin-bottom: 1.5rem;
}

.failed-title {
    color: #dc3545;
    margin-bottom: 2rem;
    font-weight: 700;
}

.failed-message {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.failed-message a {
    color: #3498db;
    text-decoration: none;
}

.failed-message a:hover {
    text-decoration: underline;
}

.failed-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Tajawal', sans-serif;
    text-align: center;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-outline {
    background: transparent;
    color: #3498db;
    border: 2px solid #3498db;
}

.btn-outline:hover {
    background: #3498db;
    color: white;
}

@media (max-width: 768px) {
    .failed-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>
@endsection

@section('content')
<section class="checkout-failed-page">
    <div class="container">
        <div class="failed-container">
            <div class="failed-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            
            <h1 class="failed-title">فشل في عملية الشراء</h1>
            
            <div class="failed-message">
                <p>عذراً، حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.</p>
                <p>إذا استمرت المشكلة، يرجى <a href="{{ route('customer.support.tickets.create') }}">الاتصال بالدعم</a>.</p>
            </div>
            
            <div class="failed-actions">
                <a href="{{ route('front.cart.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i>
                    العودة إلى السلة
                </a>
                
                <a href="{{ route('front.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i>
                    مواصلة التسوق
                </a>
                
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-tachometer-alt"></i>
                    لوحة التحكم
                </a>
            </div>
        </div>
    </div>
</section>
@endsection