@extends('frontend.Seller.dashboard.index')

@section('statistics')
<div class="row mt-4">
    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">إجمالي الطلبات!</p>
                    <h4 class="info__title">{{ $data['total_orders'] ?? 0 }}</h4>
                </div>
                <div class="info-icon icon-element bg-4">
                    <i class="la la-shopping-cart"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('seller.orders.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل<i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">إجمالي المبيعات!</p>
                    <h4 class="info__title">ر.ي {{ number_format($data['total_sales'] ?? 0, 2) }}</h4>
                </div>
                <div class="info-icon icon-element bg-3">
                    <i class="la la-money"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('seller.payments.index') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">إجمالي العملاء!</p>
                    <h4 class="info__title">{{ $data['total_customers'] ?? 0 }}</h4>
                </div>
                <div class="info-icon icon-element bg-2">
                    <i class="la la-users"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('seller.seller.statistics.users') }}" class="d-flex align-items-center justify-content-between view-all">
                مشاهدة الكل <i class="la la-angle-left"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 responsive-column-l">
        <div class="icon-box icon-layout-2 dashboard-icon-box pb-0">
            <div class="d-flex pb-3 justify-content-between">
                <div class="info-content">
                    <p class="info__desc">المنتجات!</p>
                    <h4 class="info__title">{{ $data['total_products'] ?? 0 }}</h4>
                </div>
                <div class="info-icon icon-element bg-1">
                    <i class="la la-cube"></i>
                </div>
            </div>
            <div class="section-block"></div>
            <a href="{{ route('seller.products.index') }}" class="d-flex align-items-center justify-content-between view-all">
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
                <h3 class="title">إحصائيات المبيعات</h3>
                <div class="select-contain select2-container-wrapper">
                    <select class="select-contain-select" id="chartPeriod">
                        <option value="7">آخر 7 أيام</option>
                        <option value="30">آخر 30 يوم</option>
                        <option value="90">آخر 3 أشهر</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-content">
            <!-- عنصر للتحقق من ظهور الرسوم -->
            <div id="chartDebug" style="display: none; background: #f8f9fa; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                <p id="debugInfo" class="mb-0 text-muted"></p>
            </div>
            <canvas id="salesChart" height="300"></canvas>
        </div>
    </div>
</div>

    <div class="col-lg-5 responsive-column--m">
        <div class="form-box dashboard-card">
            <div class="form-title-wrap">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">إشعارات</h3>
                    <form action="{{ route('seller.seller.notifications.markAllRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="icon-element mark-as-read-btn ms-auto me-0" data-bs-toggle="tooltip" data-placement="left" title="تحديد الكل كمقروء">
                            <i class="la la-check-square"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="form-content p-0">
                <div class="list-group drop-reveal-list">
                    @forelse($data['notifications'] ?? [] as $notification)
                    <a href="#" class="list-group-item list-group-item-action {{ $loop->first ? 'border-top-0' : '' }} {{ $notification->is_read ? '' : 'bg-light' }}">
                        <div class="msg-body d-flex align-items-center">
                            <div class="icon-element flex-shrink-0 me-3 ms-0 {{ $notification->is_read ? 'text-muted' : 'text-primary' }}">
                                <i class="la la-bell"></i>
                            </div>
                            <div class="msg-content w-100">
                                <h3 class="title pb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">{{ $notification->title }}</h3>
                                <p class="msg-text">{{ $notification->content }}</p>
                                <small class="text-muted">{{ $notification->time }}</small>
                            </div>
                            @if(!$notification->is_read)
                            <form action="{{ route('seller.seller.notifications.markAllRead', $notification->notification_id) }}" method="POST" class="d-inline">
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

    <div class="col-lg-12">
        <div class="form-box dashboard-card">
            <div class="form-title-wrap">
                <h3 class="title">المبيعات حسب الفئة</h3>
            </div>
            <div class="form-content">
                <div class="row">
                    @forelse($data['sales_by_category'] ?? [] as $category)
                    <div class="col-lg-3 responsive-column-l">
                        <div class="icon-box icon-layout-2 dashboard-icon-box dashboard--icon-box bg-{{ $loop->iteration }} pb-0">
                            <div class="d-flex pb-3 justify-content-between">
                                <div class="info-content">
                                    <p class="info__desc">{{ $category->name }}</p>
                                    <h4 class="info__title">ر.ي {{ number_format($category->amount, 2) }}</h4>
                                </div>
                                <div class="info-icon icon-element bg-white text-color-{{ $loop->iteration + 1 }}">
                                    <i class="la {{ \App\Http\Controllers\Seller\DashboardController::getCategoryIcon($category->name) }}"></i>
                                </div>
                            </div>
                            <div class="section-block"></div>
                            <a href="{{ route('seller.products.index') }}?category={{ urlencode($category->name) }}" class="d-flex align-items-center justify-content-between view-all">
                                عرض المنتجات <i class="la la-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-4">
                        <i class="la la-chart-pie fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">لا توجد مبيعات حسب الفئات حتى الآن</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script_sdebar')
<style>
#salesChart {
    width: 100% !important;
    height: 300px !important;
    display: block;
}

.form-content {
    position: relative;
    min-height: 350px;
}

/* تأكد من أن الحاوية مرئية */
.form-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* أنماط التصحيح */
#chartDebug {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 14px;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing chart...');
    
    const chartElement = document.getElementById('salesChart');
    const debugElement = document.getElementById('chartDebug');
    const debugInfo = document.getElementById('debugInfo');
    
    if (!chartElement) {
        console.error('Chart canvas element not found!');
        return;
    }
    
    // إظهار معلومات التصحيح
    debugElement.style.display = 'block';
    debugInfo.textContent = 'جاري تحميل الرسم البياني...';
    
    console.log('Chart element found:', chartElement);
    
    // التحقق من وجود Chart.js
    if (typeof Chart === 'undefined') {
        debugInfo.textContent = 'خطأ: مكتبة Chart.js غير محملة';
        console.error('Chart.js library not loaded');
        return;
    }
    
    console.log('Chart.js loaded successfully');
    
    let salesChart;
    
    try {
        // إنشاء الرسم البياني
        salesChart = new Chart(chartElement, {
            type: 'line',
            data: {
                labels: ['يوم 1', 'يوم 2', 'يوم 3', 'يوم 4', 'يوم 5', 'يوم 6', 'يوم 7'],
                datasets: [{
                    label: 'المبيعات اليومية',
                    data: [120, 190, 300, 250, 200, 300, 450],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'ر.ي ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        debugInfo.textContent = 'الرسم البياني جاهز - جاري تحميل البيانات...';
        console.log('Chart created successfully');
        
    } catch (error) {
        debugInfo.textContent = 'خطأ في إنشاء الرسم البياني: ' + error.message;
        console.error('Error creating chart:', error);
        return;
    }
    
    // تحديث البيانات حسب الفترة المحددة
    document.getElementById('chartPeriod').addEventListener('change', function() {
        const days = this.value;
        debugInfo.textContent = 'جاري تحميل بيانات ' + days + ' يوم...';
        updateChartData(days);
    });
    
    function updateChartData(days) {
        console.log('Updating chart data for days:', days);
        
        // بيانات تجريبية للاختبار
        const testData = {
            7: [120, 190, 300, 250, 200, 300, 450],
            30: [100, 150, 200, 180, 220, 250, 300, 280, 320, 350, 400, 380, 420, 450, 500, 480, 520, 550, 600, 580, 620, 650, 700, 680, 720, 750, 800, 780, 820, 850],
            90: [/* بيانات 90 يوم */]
        };
        
        // استخدام بيانات تجريبية أولاً للاختبار
        if (testData[days]) {
            const labels = Array.from({length: days}, (_, i) => `يوم ${i + 1}`);
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = testData[days];
            salesChart.update();
            debugInfo.textContent = 'تم تحميل البيانات التجريبية (' + days + ' يوم)';
            console.log('Test data loaded for', days, 'days');
            return;
        }
        
        // محاولة جلب البيانات الحقيقية من الخادم
        fetch(`/seller/dashboard/chart-data?days=${days}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received chart data:', data);
            
            if (data.labels && data.sales) {
                salesChart.data.labels = data.labels;
                salesChart.data.datasets[0].data = data.sales;
                salesChart.update();
                debugInfo.textContent = 'تم تحميل البيانات بنجاح (' + days + ' يوم)';
            } else {
                throw new Error('Invalid data format received');
            }
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            debugInfo.textContent = 'خطأ في جلب البيانات: ' + error.message + ' - استخدام بيانات تجريبية';
            
            // استخدام بيانات تجريبية كبديل
            const fallbackLabels = Array.from({length: days}, (_, i) => `يوم ${i + 1}`);
            const fallbackData = Array.from({length: days}, () => Math.floor(Math.random() * 500) + 100);
            
            salesChart.data.labels = fallbackLabels;
            salesChart.data.datasets[0].data = fallbackData;
            salesChart.update();
        });
    }
    
    // تحميل البيانات الأولية
    updateChartData(7);
    
    // إخفاء معلومات التصحيح بعد 5 ثواني
    setTimeout(() => {
        debugElement.style.display = 'none';
    }, 5000);
});
</script>
@endsection