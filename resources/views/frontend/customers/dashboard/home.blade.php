@extends('frontend.customers.dashboard.index')

@section('statistics')
<div class="row mt-4">
    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">إجمالي الطلبات!</p>
                    <h4 class="info__title">{{ $data['total_orders'] }}</h4>
                </div>
                <div class="info-icon icon-element bg-4">
                    <i class="la la-shopping-cart"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('customer.orders.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل<i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">الطلبات قيد المعالجة!</p>
                    <h4 class="info__title">{{ $data['processing_orders'] }}</h4>
                </div>
                <div class="info-icon icon-element bg-3">
                    <i class="la la-hourglass-half"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('customer.orders.index') }}?status=processing" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">المنتجات المفضلة!</p>
                    <h4 class="info__title">{{ $data['wishlist_items'] }}</h4>
                </div>
                <div class="info-icon icon-element bg-2">
                    <i class="la la-heart"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('customer.wishlist.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">التقييمات!</p>
                    <h4 class="info__title">{{ $data['total_reviews'] }}</h4>
                </div>
                <div class="info-icon icon-element bg-1">
                    <i class="la la-star"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('customer.reviews.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@section('contects')
<div class="row">
    <div class="col-lg-7 responsive-column--m">
        <div class="form-box">
            <div class="form-title-wrap">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">طلباتي الأخيرة</h3>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
            </div>
            <div class="form-content">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>المبلغ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recent_orders'] as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->order_number }}</strong>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td>ر.س {{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-sm btn-primary">
                                        <i class="la la-eye me-1"></i>تفاصيل
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="la la-shopping-cart fs-1 text-muted mb-2"></i>
                                    <p class="text-muted mb-0">لا توجد طلبات بعد</p>
                                    <a href="{{ route('front.products.index') }}" class="btn btn-primary mt-2">تسوق الآن</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 responsive-column--m">
        <div class="form-box dashboard-card">
            <div class="form-title-wrap">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">إشعارات</h3>
                    <form action="{{ route('customer.notifications.markAllRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="icon-element mark-as-read-btn ms-auto me-0" data-bs-toggle="tooltip" data-placement="left" title="تحديد الكل كمقروء">
                            <i class="la la-check-square"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="form-content p-0">
                <div class="list-group drop-reveal-list">
                    @forelse($data['notifications'] as $notification)
                    <a href="#" class="list-group-item list-group-item-action {{ $loop->first ? 'border-top-0' : '' }} {{ $notification->is_read ? '' : 'bg-light' }}">
                        <div class="msg-body d-flex align-items-center">
                            <div class="icon-element flex-shrink-0 me-3 ms-0 {{ $notification->is_read ? 'text-muted' : 'text-primary' }}">
                                <i class="la la-bell"></i>
                            </div>
                            <div class="msg-content w-100">
                                <h3 class="title pb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">{{ $notification->title }}</h3>
                                <p class="msg-text">{{ $notification->content }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!$notification->is_read)
                            <form action="{{ route('customer.notifications.markAsRead', $notification->notification_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="icon-element mark-as-read-btn flex-shrink-0 ms-auto me-0" data-bs-toggle="tooltip" data-placement="left" title="تحديد كمقروء">
                                    <i class="la la-check-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="list-group-item text-center py-4">
                        <i class="la la-bell-slash fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">لا توجد إشعارات</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="form-box dashboard-card">
            <div class="form-title-wrap">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">المنتجات التي شاهدتها مؤخراً</h3>
                    <a href="{{ route('front.products.index') }}" class="btn btn-sm btn-outline-primary">تصفح المزيد</a>
                </div>
            </div>
            <div class="form-content">
                <div class="row">
                    @forelse($data['recently_viewed'] as $product)
                    <div class="col-lg-3 col-md-6 responsive-column-l">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-3">
                                <div class="product-img mb-3">
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->title }}" 
                                         class="img-fluid rounded" style="height: 120px; object-fit: cover;">
                                </div>
                                <h6 class="product-title mb-2">{{ Str::limit($product->title, 30) }}</h6>
                                <div class="price mb-3 text-primary fw-bold">ر.س {{ number_format($product->price, 2) }}</div>
                                <a href="{{ route('front.products.show', $product->product_id) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="la la-eye me-1"></i>عرض المنتج
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-4">
                        <i class="la la-eye-slash fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-3">لا توجد منتجات مشاهدة مؤخراً</p>
                        <a href="{{ route('front.products.index') }}" class="btn btn-primary">تصفح المنتجات</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css_sdebar')
<style>
.product-card {
    transition: transform 0.2s ease-in-out;
}
.product-card:hover {
    transform: translateY(-5px);
}
.dashboard-icon-box {
    border-radius: 10px;
    overflow: hidden;
}
.view-all {
    color: #6c757d;
    text-decoration: none;
    padding: 10px 0;
    transition: color 0.2s;
}
.view-all:hover {
    color: #0d6efd;
}
</style>
@endsection