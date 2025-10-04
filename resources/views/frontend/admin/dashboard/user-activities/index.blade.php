@extends('frontend.admin.dashboard.index')

@section('title', 'نشاطات المستخدمين')

@section('contects')
<br><br><br>
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-header-title">نشاطات المستخدمين</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">نشاطات المستخدمين</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <form action="{{ route('admin.user-activities.clear-old') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning" onclick="return confirm('هل أنت متأكد من حذف النشاطات القديمة؟')">
                        <i class="la la-trash me-1"></i>حذف النشاطات القديمة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('admin.user-activities.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="activity_type" class="form-control">
                                    <option value="">جميع الأنواع</option>
                                    @foreach($activityTypes as $type)
                                        <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="من تاريخ">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="إلى تاريخ">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="la la-search me-1"></i>بحث
                                </button>
                                <a href="{{ route('admin.user-activities.index') }}" class="btn btn-secondary">
                                    <i class="la la-refresh me-1"></i>إعادة تعيين
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>نوع النشاط</th>
                            <th>الوصف</th>
                            <th>عنوان IP</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->activity_id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($activity->user->profile_photo)
                                        <img src="{{ asset($activity->user->profile_photo) }}" 
                                             alt="{{ $activity->user->name }}" 
                                             class="rounded-circle me-2" width="32" height="32">
                                    @else
                                        <img src="{{ asset('static/images/Default_pfp.jpg') }}" 
                                             alt="صورة افتراضية" 
                                             class="rounded-circle me-2" width="32" height="32">
                                    @endif
                                    <div>
                                        <strong>{{ $activity->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $activity->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $activity->activity_type }}</span>
                            </td>
                            <td>{{ Str::limit($activity->description, 50) }}</td>
                            <td>
                                <code>{{ $activity->ip_address }}</code>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $activity->created_at->format('Y-m-d H:i') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.user-activities.show', $activity->activity_id) }}" 
                                       class="btn btn-sm btn-primary" title="عرض التفاصيل">
                                        <i class="la la-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $activity->activity_id }})"
                                            title="حذف">
                                        <i class="la la-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $activity->activity_id }}" 
                                          action="{{ route('admin.user-activities.destroy', $activity->activity_id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="la la-inbox la-3x mb-2"></i>
                                <br>
                                لا توجد نشاطات لعرضها
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- الترقيم -->
            <div class="d-flex justify-content-center mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('هل أنت متأكد من حذف هذا النشاط؟')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush