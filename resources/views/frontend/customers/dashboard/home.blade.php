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
            <a href="" class="d-flex align-items-center justify-content-between view-all">
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
            <a href="" class="d-flex align-items-center justify-content-between view-all">
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
            <a href="" class="d-flex align-items-center justify-content-between view-all">
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
            <a href="" class="d-flex align-items-center justify-content-between view-all">
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
                <h3 class="title">طلباتي الأخيرة</h3>
                <a href="" class="btn btn-sm btn-outline-primary">عرض الكل</a>
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
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_text }}</span></td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <a href="" class="btn btn-sm btn-primary">تفاصيل</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد طلبات بعد</td>
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
                    <button type="button" class="icon-element mark-as-read-btn ms-auto me-0" data-bs-toggle="tooltip" data-placement="left" title="اشر عليها بانها قرات">
                        <i class="la la-check-square"></i>
                    </button>
                </div>
            </div>
            <div class="form-content p-0">
                <div class="list-group drop-reveal-list">
                    @foreach($data['notifications'] as $notification)
                    <a href="#" class="list-group-item list-group-item-action {{ $loop->first ? 'border-top-0' : '' }}">
                        <div class="msg-body d-flex align-items-center">
                            <div class="icon-element flex-shrink-0 me-3 ms-0">
                                <i class="la la-bell"></i>
                            </div>
                            <div class="msg-content w-100">
                                <h3 class="title pb-1">{{ $notification->title }}</h3>
                                <p class="msg-text">{{ $notification->content }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="icon-element mark-as-read-btn flex-shrink-0 ms-auto me-0" data-bs-toggle="tooltip" data-placement="left" title="ضع إشارة مقروء">
                                <i class="la la-check-square"></i>
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-box dashboard-card">
            <div class="form-title-wrap">
                <h3 class="title">المنتجات التي شاهدتها مؤخراً</h3>
                <a href="{{ route('front.home') }}" class="btn btn-sm btn-outline-primary">تصفح المزيد</a>
            </div>
            <div class="form-content">
                <div class="row">
                    @foreach($data['recently_viewed'] as $product)
                    <div class="col-lg-3 responsive-column-l">
                        <div class="card product-card">
                            <div class="card-body">
                                <div class="product-img">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->title }}">
                                </div>
                                <h5 class="product-title">{{ Str::limit($product->title, 30) }}</h5>
                                <div class="price">${{ number_format($product->price, 2) }}</div>
                                <a href="" class="btn btn-primary btn-sm">عرض المنتج</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
