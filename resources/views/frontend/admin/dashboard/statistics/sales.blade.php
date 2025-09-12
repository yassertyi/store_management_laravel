@extends('frontend.admin.dashboard.index')

@section('title', 'إحصائيات المبيعات')
@section('page_title', 'إحصائيات المبيعات')

@section('css_sdebar')
<!-- مكتبة Charts.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- مكتبة DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">
<style>
    .icon-box {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        margin-bottom: 20px;
    }
    .icon-box:hover { transform: translateY(-5px); }
    .chart-container { position: relative; height: 300px; margin-bottom: 20px; }
    .filter-btn { border-radius: 20px; margin: 0 5px; }
    .filter-btn.active { background-color: #2e59d9; color: white; }
    .dashboard-icon-box { padding: 20px; }
    .info-icon {
        width: 50px; height: 50px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .bg-1 { background-color: rgba(46, 89, 217, 0.1); color: #2e59d9; }
    .bg-2 { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .bg-3 { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .bg-4 { background-color: rgba(253, 126, 20, 0.1); color: #fd7e14; }
    .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600; }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-processing { background-color: #cce5ff; color: #004085; }
    .status-completed { background-color: #d4edda; color: #155724; }
    .status-cancelled { background-color: #f8d7da; color: #721c24; }
    .status-refunded { background-color: #e2e3e5; color: #383d41; }
</style>
@endsection

@section('contects')
<br> 
<div class="container-fluid">
    <!-- بطاقات الإحصائيات -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>إجمالي الإيرادات</p>
                        <h4>{{ number_format($totalRevenue, 2) }} ر.ي</h4>
                    </div>
                    <div class="info-icon bg-1"><i class="la la-money"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>إجمالي الطلبات</p>
                        <h4>{{ $totalOrdersCount }}</h4>
                    </div>
                    <div class="info-icon bg-2"><i class="la la-shopping-cart"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>متوسط قيمة الطلب</p>
                        <h4>{{ number_format($averageOrder, 2) }} ر.ي</h4>
                    </div>
                    <div class="info-icon bg-3"><i class="la la-chart-line"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>الطلبات النشطة</p>
                        <h4>{{ $ordersByStatus->where('status', 'processing')->first()->count ?? 0 }}</h4>
                    </div>
                    <div class="info-icon bg-4"><i class="la la-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار التصفية -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary filter-btn active" data-range="7">آخر أسبوع</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-range="30">آخر شهر</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-range="90">آخر 3 أشهر</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-range="365">آخر سنة</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المخططات -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h6>الإيرادات اليومية</h6></div>
                <div class="card-body"><canvas id="dailySalesChart"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h6>حالة الطلبات</h6></div>
                <div class="card-body"><canvas id="orderStatusChart"></canvas></div>
            </div>
        </div>
    </div>

    <!-- جدول الطلبات -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h6>الطلبات الحديثة</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">تصفية حسب الحالة</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item filter-status" data-status="all" href="#">الكل</a></li>
                            <li><a class="dropdown-item filter-status" data-status="pending" href="#">قيد الانتظار</a></li>
                            <li><a class="dropdown-item filter-status" data-status="processing" href="#">قيد المعالجة</a></li>
                            <li><a class="dropdown-item filter-status" data-status="completed" href="#">مكتمل</a></li>
                            <li><a class="dropdown-item filter-status" data-status="cancelled" href="#">ملغي</a></li>
                            <li><a class="dropdown-item filter-status" data-status="refunded" href="#">تم الاسترجاع</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <table id="ordersTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>التاريخ</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->customer->user->name ?? 'عميل' }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>{{ number_format($order->total_amount, 2) }} ر.ي</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'status-pending',
                                            'processing' => 'status-processing',
                                            'completed' => 'status-completed',
                                            'cancelled' => 'status-cancelled',
                                            'refunded' => 'status-refunded'
                                        ][$order->status] ?? 'status-pending';
                                        
                                        $statusText = [
                                            'pending' => 'قيد الانتظار',
                                            'processing' => 'قيد المعالجة',
                                            'completed' => 'مكتمل',
                                            'cancelled' => 'ملغي',
                                            'refunded' => 'تم الاسترجاع'
                                        ][$order->status] ?? $order->status;
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="la la-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<!-- DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // بيانات المخططات
    const dailyOrdersData = @json($dailyOrders);
    const ordersByStatusData = @json($ordersByStatus);

    // الإيرادات اليومية
    const dates = dailyOrdersData.map(item => new Date(item.date).toLocaleDateString('ar-EG'));
    const revenues = dailyOrdersData.map(item => item.total_revenue);

    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySalesChart = new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'الإيرادات',
                data: revenues,
                backgroundColor: 'rgba(46, 89, 217, 0.05)',
                borderColor: 'rgba(46, 89, 217, 1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: { label: ctx => 'الإيرادات: ' + ctx.parsed.y + ' ر.ي' }
                }
            }
        }
    });

    // حالة الطلبات
    const statusLabels = ordersByStatusData.map(item => ({
        'pending': 'قيد الانتظار',
        'processing': 'قيد المعالجة',
        'completed': 'مكتمل',
        'cancelled': 'ملغي',
        'refunded': 'تم الاسترجاع'
    }[item.status] || item.status));

    const statusData = ordersByStatusData.map(item => item.count);
    const statusColors = ['#ffc107', '#007bff', '#28a745', '#dc3545', '#6c757d'];

    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: statusColors }] },
        options: { plugins: { legend: { position: 'bottom', rtl: true } } }
    });

    // DataTable
    $(document).ready(function() {
        var table = $('#ordersTable').DataTable({
            responsive: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json' }
        });

        $('.filter-status').on('click', function(e) {
            e.preventDefault();
            var status = $(this).data('status');
            table.columns(4).search(status === 'all' ? '' : status).draw();
        });
    });
    
</script>
@endsection
