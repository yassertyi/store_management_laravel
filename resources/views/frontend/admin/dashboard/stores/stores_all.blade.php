@extends('frontend.admin.dashboard.index')

@section('title', 'المتاجر')
@section('page_title', 'إدارة المتاجر')

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
                            <h3 class="title">قائمة المتاجر</h3>
                            <a href="{{ route('admin.stores.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> متجر جديد
                            </a>
                        </div>

                        <div class="form-content">
                            <div class="table-form table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>رقم</th>
                                            <th>اسم المتجر</th>
                                            <th>المالك</th>
                                            <th>الشعار</th>
                                            <th>الحالة</th>
                                            <th>التحكم</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stores as $store)
                                            <tr>
                                                <td>{{ $store->store_id }}</td>
                                                <td>{{ $store->store_name }}</td>
                                                <td>{{ $store->user->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    @if ($store->logo)
                                                        <img src="{{ asset('static/images/stors/' . $store->logo) }}"
                                                            width="50" class="rounded">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($store->status === 'active')
                                                        <span class="badge text-bg-success">نشط</span>
                                                    @elseif($store->status === 'inactive')
                                                        <span class="badge text-bg-warning">غير نشط</span>
                                                    @else
                                                        <span class="badge text-bg-danger">موقوف</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.stores.show', $store) }}"
                                                        class="theme-btn theme-btn-small me-2" data-bs-toggle="tooltip"
                                                        title="عرض">
                                                        <i class="la la-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.stores.edit', $store->store_id) }}"
                                                        class="theme-btn theme-btn-small me-2"><i
                                                            class="la la-edit"></i></a>
                                                    <form action="{{ route('admin.stores.destroy', $store->store_id) }}"
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
                                {{ $stores->links() }}
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
