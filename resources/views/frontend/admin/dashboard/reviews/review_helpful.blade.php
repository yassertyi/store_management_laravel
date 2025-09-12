@extends('frontend.admin.dashboard.index')

@section('title', 'تصويتات التقييمات')
@section('page_title', 'إدارة تصويتات التقييمات')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة تصويتات التقييمات</h3>
                            <p class="font-size-14">
                                إظهار {{ $helpfuls->firstItem() }} إلى {{ $helpfuls->lastItem() }} من أصل {{ $helpfuls->total() }} تصويت
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.review-helpful.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> إضافة تصويت جديد
                            </a>
                        </div>
                    </div>

                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>رقم</th>
                                        <th>التقييم</th>
                                        <th>المستخدم</th>
                                        <th>مفيد؟</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($helpfuls as $helpful)
                                        <tr>
                                            <td>{{ $helpful->helpful_id }}</td>
                                            <td>{{ $helpful->review->title ?? '---' }}</td>
                                            <td>{{ $helpful->user->name ?? '---' }}</td>
                                            <td>
                                                @if($helpful->is_helpful)
                                                    <span class="badge text-bg-success">نعم</span>
                                                @else
                                                    <span class="badge text-bg-danger">لا</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.review-helpful.edit', $helpful->helpful_id) }}"
                                                       class="theme-btn theme-btn-small me-2">
                                                        <i class="la la-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.review-helpful.destroy', $helpful->helpful_id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا التصويت؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="theme-btn theme-btn-small bg-danger">
                                                            <i class="la la-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $helpfuls->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
    setTimeout(function() {
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
</script>
@endsection
