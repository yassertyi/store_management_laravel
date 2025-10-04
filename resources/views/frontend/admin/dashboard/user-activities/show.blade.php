@extends('frontend.admin.dashboard.index')

@section('title', 'تفاصيل النشاط')

@section('contects')
<br><br><br>
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-header-title">تفاصيل النشاط</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user-activities.index') }}">نشاطات المستخدمين</a></li>
                        <li class="breadcrumb-item active">تفاصيل النشاط</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-activities.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-right me-1"></i>العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات النشاط</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">المستخدم:</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($activity->user->profile_photo)
                                                <img src="{{ asset($activity->user->profile_photo) }}" 
                                                     alt="{{ $activity->user->name }}" 
                                                     class="rounded-circle me-2" width="40" height="40">
                                            @else
                                                <img src="{{ asset('static/images/Default_pfp.jpg') }}" 
                                                     alt="صورة افتراضية" 
                                                     class="rounded-circle me-2" width="40" height="40">
                                            @endif
                                            <div>
                                                <strong>{{ $activity->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $activity->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>نوع النشاط:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $activity->activity_type }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>عنوان IP:</th>
                                    <td><code>{{ $activity->ip_address }}</code></td>
                                </tr>
                                <tr>
                                    <th>التاريخ والوقت:</th>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>وصف النشاط:</h6>
                            <div class="alert alert-info">
                                {{ $activity->description }}
                            </div>
                        </div>
                    </div>

                    @if($activity->user_agent)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>معلومات المتصفح:</h6>
                            <div class="bg-light p-3 rounded">
                                <code class="small">{{ $activity->user_agent }}</code>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.user-activities.index') }}" class="btn btn-primary">
                            <i class="la la-list me-1"></i>العودة للقائمة
                        </a>
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="confirmDelete({{ $activity->activity_id }})">
                            <i class="la la-trash me-1"></i>حذف النشاط
                        </button>
                        <form id="delete-form-{{ $activity->activity_id }}" 
                              action="{{ route('admin.user-activities.destroy', $activity->activity_id) }}" 
                              method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات إضافية</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        <i class="la la-info-circle me-1"></i>
                        يتم تسجيل النشاطات تلقائياً عند قيام المستخدمين بتنفيذ إجراءات مختلفة في النظام.
                    </p>
                </div>
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