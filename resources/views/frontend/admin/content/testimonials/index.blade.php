{{-- resources/views/frontend/admin/content/testimonials/index.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', 'آراء العملاء')
@section('page_title', 'إدارة آراء العملاء')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">

            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">قائمة آراء العملاء</h3>
                    <a href="{{ route('admin.content.testimonials.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> رأي جديد
                    </a>
                </div>

                <div class="form-content">
                    <div class="table-form table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>اسم العميل</th>
                                    <th>المكان</th>
                                    <th>التقييم</th>
                                    <th>المحتوى</th>
                                    <th>الحالة</th>
                                    <th>الترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $testimonial)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($testimonial->customer_image)
                                                <img src="{{ asset('storage/' . $testimonial->customer_image) }}" alt="{{ $testimonial->customer_name }}" width="50" height="50" class="rounded-circle object-fit-cover">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="la la-user text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $testimonial->customer_name }}</td>
                                        <td>{{ $testimonial->location ?? '-' }}</td>
                                        <td>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="la la-star{{ $i <= $testimonial->rating ? ' text-warning' : ' text-light' }}"></i>
                                            @endfor
                                            <small class="text-muted">({{ $testimonial->rating }}/5)</small>
                                        </td>
                                        <td>{{ Str::limit($testimonial->content, 50) }}</td>
                                        <td>
                                            <form action="{{ route('admin.content.testimonials.toggle-status', $testimonial) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $testimonial->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $testimonial->is_active ? 'مفعل' : 'معطل' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $testimonial->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('admin.content.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-primary me-1" title="تعديل">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.content.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('هل أنت متأكد من حذف هذا الرأي؟')" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </form>
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