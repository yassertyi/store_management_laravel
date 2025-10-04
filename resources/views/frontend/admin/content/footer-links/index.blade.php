{{-- resources/views/frontend/admin/content/footer-links/index.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', 'روابط الفوتر')
@section('page_title', 'إدارة روابط الفوتر')

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
                    <h3 class="title">قائمة روابط الفوتر</h3>
                    <a href="{{ route('admin.content.footer-links.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> رابط جديد
                    </a>
                </div>

                <div class="form-content">
                    <div class="table-form table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>الرابط</th>
                                    <th>القسم</th>
                                    <th>الحالة</th>
                                    <th>الترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($footerLinks as $link)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $link->title }}</td>
                                        <td>
                                            <a href="{{ $link->url }}" target="_blank" class="text-primary">
                                                {{ Str::limit($link->url, 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            @switch($link->section)
                                                @case('store')
                                                    <span class="badge bg-primary">المتجر</span>
                                                    @break
                                                @case('customer_service')
                                                    <span class="badge bg-success">خدمة العملاء</span>
                                                    @break
                                                @case('information')
                                                    <span class="badge bg-info">معلومات</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $link->section }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.content.footer-links.toggle-status', $link) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $link->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $link->is_active ? 'مفعل' : 'معطل' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $link->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('admin.content.footer-links.edit', $link) }}" class="theme-btn theme-btn-small me-2">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.content.footer-links.destroy', $link) }}" method="POST" class="d-inline">
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