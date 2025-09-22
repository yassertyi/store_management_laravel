@extends('frontend.Seller.dashboard.index')

@section('title', 'إحصائيات المنتجات')
@section('page_title', 'إحصائيات المنتجات')

@section('css_sdebar')
<!-- مكتبة Charts.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- مكتبة DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">

<style>
    .icon-box {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        margin-bottom: 20px;
    }
    .icon-box:hover { transform: translateY(-5px); }
    .dashboard-icon-box { padding: 20px; }
    .info-icon {
        width: 50px; height: 50px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .bg-1 { background-color: rgba(46, 89, 217, 0.1); color: #2e59d9; }
    .bg-2 { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .bg-3 { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
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
                        <p>إجمالي المنتجات</p>
                        <h4>{{ $totalProducts }}</h4>
                    </div>
                    <div class="info-icon bg-1"><i class="la la-box"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>المنتجات النشطة</p>
                        <h4>{{ $activeProducts }}</h4>
                    </div>
                    <div class="info-icon bg-2"><i class="la la-check-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>المنتجات المميزة</p>
                        <h4>{{ $featuredProducts }}</h4>
                    </div>
                    <div class="info-icon bg-3"><i class="la la-star"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- المخطط اليومي للإضافات -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header"><h6>الإضافات اليومية للمنتجات (آخر 30 يوم)</h6></div>
                <div class="card-body">
                    <canvas id="dailyProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول أحدث 10 منتجات -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header"><h6>أحدث 10 منتجات</h6></div>
                <div class="card-body">
                    <table id="productsTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>عنوان المنتج</th>
                                <th>المتجر</th>
                                <th>القسم</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>مميز</th>
                                <th>تاريخ الإضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                            <tr>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->store->name ?? '-' }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->price }} {{ $product->currency ?? 'USD' }}</td>
                                <td>{{ $product->status == 'active' ? 'نشط' : ($product->status == 'inactive' ? 'معطل' : 'مسودة') }}</td>
                                <td>{{ $product->is_featured ? 'نعم' : 'لا' }}</td>
                                <td>{{ $product->created_at->format('Y-m-d') }}</td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // بيانات الإضافات اليومية للمنتجات
    const dailyProductsData = @json($dailyProducts);
    const productDates = dailyProductsData.map(item => new Date(item.date).toLocaleDateString('ar-EG'));
    const productCounts = dailyProductsData.map(item => item.count);

    const dailyProdsCtx = document.getElementById('dailyProductsChart').getContext('2d');
    new Chart(dailyProdsCtx, {
        type: 'line',
        data: {
            labels: productDates,
            datasets: [{
                label: 'عدد المنتجات',
                data: productCounts,
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
                    callbacks: {
                        label: ctx => 'عدد المنتجات: ' + ctx.parsed.y
                    }
                }
            }
        }
    });

    // تفعيل DataTable
    $(document).ready(function() {
        $('#productsTable').DataTable({
            responsive: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json' }
        });
    });
</script>
@endsection
