@extends('frontend.admin.dashboard.index')

@section('title', 'إحصائيات المستخدمين')
@section('page_title', 'إحصائيات المستخدمين')

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
                        <p>إجمالي المستخدمين</p>
                        <h4>{{ $totalUsers }}</h4>
                    </div>
                    <div class="info-icon bg-1"><i class="la la-users"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>المستخدمون الجدد (آخر 30 يوم)</p>
                        <h4>{{ $newUsers }}</h4>
                    </div>
                    <div class="info-icon bg-2"><i class="la la-user-plus"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="icon-box dashboard-icon-box">
                <div class="d-flex pb-3 justify-content-between">
                    <div>
                        <p>المستخدمون النشطون (آخر 7 أيام)</p>
                        <h4>{{ $activeUsers }}</h4>
                    </div>
                    <div class="info-icon bg-3"><i class="la la-user-check"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- المخطط اليومي للتسجيلات -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header"><h6>التسجيلات اليومية (آخر 30 يوم)</h6></div>
                <div class="card-body">
                    <canvas id="dailyRegistrationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول أحدث 10 مستخدمين -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header"><h6>أحدث 10 مستخدمين</h6></div>
                <div class="card-body">
                    <table id="usersTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>نوع المستخدم</th>
                                <th>الحالة</th>
                                <th>تاريخ التسجيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @if($user->user_type == 0) عميل
                                    @elseif($user->user_type == 1) بائع
                                    @else مسؤول @endif
                                </td>
                                <td>{{ $user->active ? 'نشط' : 'معطل' }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
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
    // بيانات التسجيلات اليومية
    const dailyRegistrationsData = @json($dailyRegistrations);
    const regDates = dailyRegistrationsData.map(item => new Date(item.date).toLocaleDateString('ar-EG'));
    const regCounts = dailyRegistrationsData.map(item => item.count);

    const dailyRegsCtx = document.getElementById('dailyRegistrationsChart').getContext('2d');
    new Chart(dailyRegsCtx, {
        type: 'line',
        data: {
            labels: regDates,
            datasets: [{
                label: 'عدد المستخدمين',
                data: regCounts,
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
                        label: ctx => 'عدد المستخدمين: ' + ctx.parsed.y
                    }
                }
            }
        }
    });

    // تفعيل DataTable
    $(document).ready(function() {
        $('#usersTable').DataTable({
            responsive: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json' }
        });
    });
</script>
@endsection
