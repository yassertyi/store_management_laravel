@extends('frontend.admin.dashboard.index')

@section('title', 'وسائل التواصل')
@section('page_title', 'إدارة وسائل التواصل')

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
                    <h3 class="title">قائمة وسائل التواصل</h3>
                    <a href="{{ route('admin.content.social-media.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> وسيلة جديدة
                    </a>
                </div>

                <div class="form-content">
                    <div class="table-form table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المنصة</th>
                                    <th>الرابط</th>
                                    <th>أيقونة</th>
                                    <th>الحالة</th>
                                    <th>الترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($socialMedia as $social)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @switch($social->platform)
                                                @case('facebook')
                                                    <span class="badge bg-primary">Facebook</span>
                                                    @break
                                                @case('twitter')
                                                    <span class="badge bg-info">Twitter</span>
                                                    @break
                                                @case('instagram')
                                                    <span class="badge bg-danger">Instagram</span>
                                                    @break
                                                @case('youtube')
                                                    <span class="badge bg-danger">YouTube</span>
                                                    @break
                                                @case('linkedin')
                                                    <span class="badge bg-primary">LinkedIn</span>
                                                    @break
                                                @case('snapchat')
                                                    <span class="badge bg-warning">Snapchat</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $social->platform }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ $social->url }}" target="_blank" class="text-primary">
                                                {{ Str::limit($social->url, 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($social->icon_class)
                                                <i class="{{ $social->icon_class }}"></i>
                                                <small class="text-muted">{{ $social->icon_class }}</small>
                                            @else
                                                <span class="text-muted">لا يوجد أيقونة</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.content.social-media.toggle-status', $social) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $social->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $social->is_active ? 'مفعل' : 'معطل' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $social->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('admin.content.social-media.edit', $social) }}" class="theme-btn theme-btn-small me-2">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.content.social-media.destroy', $social) }}" method="POST" class="d-inline">
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