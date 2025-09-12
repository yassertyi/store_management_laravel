@extends('frontend.admin.dashboard.index')

@section('title', 'التصنيفات')
@section('page_title', 'إدارة التصنيفات')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="container-fluid">

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

            <div class="form-box">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <h3 class="title">قائمة التصنيفات</h3>
                        <a href="{{ route('admin.categories.create') }}" class="theme-btn theme-btn-small">
                            <i class="la la-plus"></i> تصنيف جديد
                        </a>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>الوصف</th>
                                        <th>التصنيف الأب</th>
                                        <th>الصورة</th>
                                        <th>الترتيب</th>
                                        <th>التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->category_id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td>{{ $category->parent->name ?? '---' }}</td>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset('storage/'.$category->image) }}" width="50" class="rounded">
                                                @endif
                                            </td>
                                            <td>{{ $category->sort_order }}</td>
                                            <td>
                                                <a href="{{ route('admin.categories.edit', $category->category_id) }}"
                                                    class="theme-btn theme-btn-small me-2"><i class="la la-edit"></i></a>
                                                <form action="{{ route('admin.categories.destroy', $category->category_id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                        class="theme-btn theme-btn-small bg-danger">
                                                        <i class="la la-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $categories->links() }}
                        </div>
                    </div>
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
