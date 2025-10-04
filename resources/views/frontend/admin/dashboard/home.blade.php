@extends('frontend.admin.dashboard.index')

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
            <a href="{{ route('admin.orders.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل<i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">إجمالي المبيعات!</p>
                    <h4 class="info__title">ريال{{ number_format($data['total_sales']) }}</h4>
                </div>
                <div class="info-icon icon-element bg-3">
                    <i class="la la-money"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('admin.payments.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">إجمالي العملاء!</p>
                    <h4 class="info__title">{{ $data['total_customers'] }}</h4>
                </div>
                <div class="info-icon icon-element bg-2">
                    <i class="la la-users"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('admin.customers.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">المنتجات!</p>
                    <h4 class="info__title">{{ $data['total_products'] }}</h4>
                </div>
                <div class="info-icon icon-element bg-1">
                    <i class="la la-cube"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('admin.products.index') }}" class="d-flex align-items-center justify-content-between view-all">
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
                <div class="d-flex align-items-center justify-content-between">
                    <ul class="chart-pagination d-flex">
                        <li><a href="#" class="theme-btn theme-btn-small me-2">يوم</a></li>
                        <li><a href="#" class="theme-btn theme-btn-small theme-btn-transparent me-2">أسبوع</a></li>
                        <li><a href="#" class="theme-btn theme-btn-small theme-btn-transparent">هذا الشهر</a></li>
                    </ul>
                    <div class="select-contain select2-container-wrapper">
                        <select class="select-contain-select">
                            <option value="كانون الثاني">كانون الثاني</option>
                            <option value="شهر فبراير">شهر فبراير</option>
                            <option value="مارس">مارس</option>
                            <option value="أبريل">أبريل</option>
                            <option value="مايو">مايو</option>
                            <option value="يونيو">يونيو</option>
                            <option value="يوليو">يوليو</option>
                            <option value="أغسطس">أغسطس</option>
                            <option value="سبتمبر">سبتمبر</option>
                            <option value="اكتوبر">اكتوبر</option>
                            <option value="شهر نوفمبر">شهر نوفمبر</option>
                            <option value="ديسمبر">ديسمبر</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-content">
                <canvas id="line-chart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-5 responsive-column--m">
    <div class="form-box dashboard-card">
        <div class="form-title-wrap">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="title">إشعارات</h3>
                <button type="button" 
                        class="icon-element mark-all-read-btn ms-auto me-0"
                        data-url="{{ route('admin.admin.notifications.markAllRead') }}"
                        data-bs-toggle="tooltip" 
                        data-placement="left" 
                        title="اشر عليها بأنها قرأت">
                    <i class="la la-check-square"></i>
                </button>
            </div>
        </div>
        <div class="form-content p-0">
            <div class="list-group drop-reveal-list">
                @foreach($notifications as $notification)
                    <a href="#" class="list-group-item list-group-item-action {{ $loop->first ? 'border-top-0' : '' }} {{ $notification->is_read ? '' : 'bg-light' }}">
                        <div class="msg-body d-flex align-items-center">
                            <div class="icon-element flex-shrink-0 me-3 ms-0">
                                <i class="la la-bell"></i>
                            </div>
                            <div class="msg-content w-100">
                                <h3 class="title pb-1">{{ $notification->message }}</h3>
                                <p class="msg-text">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->is_read)
                                <span class="icon-element mark-as-read-btn flex-shrink-0 ms-auto me-0"
                                      data-url="{{ route('admin.admin.notifications.markAllRead', $notification->id) }}"
                                      data-bs-toggle="tooltip" 
                                      data-placement="left" 
                                      title="ضع إشارة مقروء">
                                    <i class="la la-check-square"></i>
                                </span>
                            @endif
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
                <h3 class="title">المبيعات حسب الفئة</h3>
            </div>
            <div class="form-content">
                <div class="row">
                    @foreach($data['sales_by_category'] as $category)
                    <div class="col-lg-3 responsive-column-l">
                        <div class="icon-box icon-layout-2 dashboard-icon-box dashboard--icon-box bg-{{ $loop->iteration }} pb-0">
                            <div class="d-flex pb-3 justify-content-between">
                                <div class="info-content">
                                    <p class="info__desc">{{ $category->name }}</p>
                                    <h4 class="info__title">ريال{{ number_format($category->amount) }}</h4>
                                </div>
                                <div class="info-icon icon-element bg-white text-color-{{ $loop->iteration + 1 }}">
                                    <i class="la {{ $loop->iteration == 1 ? 'la-laptop' : ($loop->iteration == 2 ? 'la-tshirt' : ($loop->iteration == 3 ? 'la-couch' : 'la-gem')) }}"></i>
                                </div>
                            </div>
                            <div class="section-block"></div>
                            <a href="{{ route('admin.payments.index') }}" class="d-flex align-items-center justify-content-between view-all">
                                عرض التفاصيل <i class="la la-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script_sdebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // تحديد إشعار كمقروء
    $(document).on("click", ".mark-as-read-btn", function(e){
        e.preventDefault();
        let url = $(this).data("url");
        let btn = $(this);

        $.ajax({
            url: url,
            method: "POST",
            data: {_token: "{{ csrf_token() }}"},
            success: function(res){
                if(res.status === "success"){
                    btn.closest(".list-group-item").removeClass("bg-light");
                    btn.remove();
                    // تحديث العداد في الجرس
                    let count = $(".noti-count").text();
                    $(".noti-count").text(Math.max(0, count - 1));
                }
            }
        });
    });

    // تحديد الكل كمقروء
    $(".mark-all-read-btn").click(function(e){
        e.preventDefault();
        let url = $(this).data("url");

        $.ajax({
            url: url,
            method: "POST",
            data: {_token: "{{ csrf_token() }}"},
            success: function(res){
                if(res.status === "success"){
                    $(".list-group-item").removeClass("bg-light");
                    $(".mark-as-read-btn").remove();
                    $(".noti-count").text(0);
                }
            }
        });
    });
});
</script>
@endpush
