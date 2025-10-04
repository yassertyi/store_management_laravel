@extends('frontend.Seller.dashboard.index')

@section('title', 'إحصائيات المستخدمين')
@section('page_title', 'إحصائيات المستخدمين')

@section('css_sdebar')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">

<style>
    .icon-box {
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: 1px solid #f0f0f0;
    }
    .icon-box:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .dashboard-icon-box { 
        padding: 25px; 
        background: white;
    }
    .info-icon {
        width: 60px; 
        height: 60px; 
        border-radius: 50%;
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-size: 1.5rem;
    }
    .bg-1 { background: linear-gradient(135deg, #2e59d9, #667eea); color: white; }
    .bg-2 { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .bg-3 { background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .card {
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 15px;
        margin-bottom: 25px;
    }
    .card-header {
        background: white;
        border-bottom: 1px solid #eee;
        border-radius: 15px 15px 0 0 !important;
        padding: 20px;
    }
    .card-header h6 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-left: 15px;
    }
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin-left: 5px;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-weight: 600;
    }
</style>
@endsection

@section('contects')
<div class="container-fluid">
    
    <!-- فلترة الفترة الزمنية -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">إحصائيات العملاء</h5>
                        </div>
                        <div class="col-md-6 text-left">
                            <select class="form-select" id="periodFilter" style="max-width: 200px;">
                                <option value="7">آخر 7 أيام</option>
                                <option value="30" selected>آخر 30 يوم</option>
                                <option value="90">آخر 90 يوم</option>
                                <option value="365">آخر سنة</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">إجمالي العملاء</div>
                        <h3 class="stat-number text-primary" id="totalUsers">{{ number_format($totalUsers) }}</h3>
                        <small class="text-muted">عملاء اشتروا من متجرك</small>
                    </div>
                    <div class="info-icon bg-1">
                        <i class="la la-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">عملاء جدد</div>
                        <h3 class="stat-number text-success" id="newUsers">{{ number_format($newUsers) }}</h3>
                        <small class="text-muted">خلال آخر 30 يوم</small>
                    </div>
                    <div class="info-icon bg-2">
                        <i class="la la-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">عملاء نشطون</div>
                        <h3 class="stat-number text-warning" id="activeUsers">{{ number_format($activeUsers) }}</h3>
                        <small class="text-muted">دخلوا خلال آخر 7 أيام</small>
                    </div>
                    <div class="info-icon bg-3">
                        <i class="la la-user-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">متوسط المشتريات</div>
                        <h3 class="stat-number text-info" id="avgOrders">
                            {{ number_format($recentUsers->avg('orders_count') ?? 0, 1) }}
                        </h3>
                        <small class="text-muted">طلبات لكل عميل</small>
                    </div>
                    <div class="info-icon" style="background: linear-gradient(135deg, #17a2b8, #6f42c1); color: white;">
                        <i class="la la-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المخططات -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>التسجيلات اليومية للعملاء</h6>
                    <div class="chart-legend">
                        <span class="legend-item">
                            <span class="legend-color" style="background-color: #2e59d9"></span>
                            عدد العملاء الجدد
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="dailyRegistrationsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول العملاء -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6>أحدث 10 عملاء</h6>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>رقم الهاتف</th>
                                    <th>عدد الطلبات</th>
                                    <th>إجمالي المشتريات</th>
                                    <th>الحالة</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>آخر دخول</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded me-2">
                                                <div class="avatar-title bg-soft-primary text-primary rounded">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary">
                                            {{ $user->orders_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ number_format($user->total_spent ?? 0, 2) }} ر.ي
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->active ? 'success' : 'danger' }}">
                                            {{ $user->active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ $user->last_login_at->diffForHumans() }}
                                        @else
                                            <span class="text-muted">لم يدخل بعد</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="la la-users la-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد بيانات للعملاء حتى الآن</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script_sdebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // بيانات التسجيلات اليومية
    const dailyRegistrationsData = @json($dailyRegistrations);
    
    // تحضير البيانات للمخطط
    const regDates = dailyRegistrationsData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('ar-EG', { 
            month: 'short', 
            day: 'numeric' 
        });
    });
    
    const regCounts = dailyRegistrationsData.map(item => item.count);

    // إنشاء المخطط
    let dailyRegsChart;
    function initializeChart() {
        const dailyRegsCtx = document.getElementById('dailyRegistrationsChart').getContext('2d');
        dailyRegsChart = new Chart(dailyRegsCtx, {
            type: 'line',
            data: {
                labels: regDates,
                datasets: [{
                    label: 'عدد العملاء الجدد',
                    data: regCounts,
                    backgroundColor: 'rgba(46, 89, 217, 0.1)',
                    borderColor: 'rgba(46, 89, 217, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgba(46, 89, 217, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return `عدد العملاء: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
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
    }

    // تفعيل DataTable
    $(document).ready(function() {
        @if($recentUsers->count() > 0)
        $('#usersTable').DataTable({
            responsive: true,
            order: [[6, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json'
            },
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ]
        });
        @endif

        // تهيئة المخطط
        initializeChart();

        // فلترة الفترة الزمنية
        $('#periodFilter').change(function() {
            const period = $(this).val();
            loadData(period);
        });
    });

    // دالة لتحديث البيانات
    function loadData(period) {
        $.ajax({
            url: '{{ route("seller.seller.statistics.users.data") }}',
            type: 'GET',
            data: { period: period },
            beforeSend: function() {
                // إظهار مؤشر التحميل
                $('#periodFilter').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // تحديث البطاقات الإحصائية
                    updateStatsCards(response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('خطأ في تحميل البيانات:', error);
                alert('حدث خطأ أثناء تحميل البيانات');
            },
            complete: function() {
                $('#periodFilter').prop('disabled', false);
            }
        });
    }

    // دالة لتحديث البطاقات الإحصائية
    function updateStatsCards(data) {
        $('#totalUsers').text(data.totalUsers.toLocaleString());
        $('#newUsers').text(data.newUsers.toLocaleString());
        $('#activeUsers').text(data.activeUsers.toLocaleString());
        
        // تحديث النص التوضيحي للفترة
        $('.icon-box small.text-muted').each(function() {
            let text = $(this).text();
            if (text.includes('آخر 30 يوم')) {
                $(this).text(text.replace('30', $('#periodFilter').val()));
            }
        });
    }

    // دالة لتحديث المخطط (يمكن تطويرها لاحقاً)
    function updateChart(chartData) {
        if (dailyRegsChart && chartData) {
            dailyRegsChart.data.labels = chartData.labels;
            dailyRegsChart.data.datasets[0].data = chartData.data;
            dailyRegsChart.update();
        }
    }
</script>
@endsection