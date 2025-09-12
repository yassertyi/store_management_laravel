@extends('frontend.admin.dashboard.index')

@section('title', 'المنتجات')
@section('page_title', 'إدارة المنتجات')

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
                    <h3 class="title">قائمة المنتجات</h3>
                    <a href="{{ route('admin.products.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> منتج جديد
                    </a>
                </div>

                <div class="form-content">
                    <div class="table-form table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المنتج</th>
                                    <th>المتجر</th>
                                    <th>السعر</th>
                                    <th>الحالة</th>
                                    <th>الصور</th>
                                    <th>المتغيرات</th>
                                    <th>التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                        <td>{{ $product->title }}</td>
                                        <td>{{ $product->store->store_name ?? 'غير محدد' }}</td>
                                        <td>{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if ($product->status === 'active')
                                                <span class="badge text-bg-success">نشط</span>
                                            @elseif($product->status === 'inactive')
                                                <span class="badge text-bg-warning">غير نشط</span>
                                            @else
                                                <span class="badge text-bg-danger">موقوف</span>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($product->images as $img)
                                                <img src="{{ asset($img->image_path) }}" width="50" class="rounded me-1 mb-1">

                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($product->variants as $var)
                                                <div>{{ $var->name }} - {{ number_format($var->price, 2) }} -
                                                    {{ $var->stock }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                class="theme-btn theme-btn-small me-2"><i class="la la-eye"></i></a>
                                            <a href="{{ route('admin.products.edit', ['product' => $product->product_id, 'redirect_to' => url()->current()]) }}"
                                                class="theme-btn theme-btn-small me-2">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product->product_id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                    class="theme-btn theme-btn-small bg-danger">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $products->links() }}
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
