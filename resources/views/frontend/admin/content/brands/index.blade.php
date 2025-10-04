{{-- resources/views/frontend/admin/content/brands/index.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', 'العلامات التجارية')
@section('page_title', 'إدارة العلامات التجارية')

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
                    <h3 class="title">قائمة العلامات التجارية</h3>
                    <a href="{{ route('admin.content.brands.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> علامة تجارية جديدة
                    </a>
                </div>

                <div class="form-content">
                    <div class="table-form table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الشعار</th>
                                    <th>الاسم</th>
                                    <th>الموقع الإلكتروني</th>
                                    <th>الحالة</th>
                                    <th>الترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($brand->logo)
                                                <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" width="50" class="rounded">
                                            @else
                                                <span class="text-muted">لا يوجد شعار</span>
                                            @endif
                                        </td>
                                        <td>{{ $brand->name }}</td>
                                        <td>
                                            @if($brand->website)
                                                <a href="{{ $brand->website }}" target="_blank" class="text-primary">زيارة الموقع</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.content.brands.toggle-status', $brand) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $brand->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $brand->is_active ? 'مفعل' : 'معطل' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $brand->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('admin.content.brands.edit', $brand) }}" class="theme-btn theme-btn-small me-2">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.content.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="theme-btn theme-btn-small bg-danger">
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