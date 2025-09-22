@extends('frontend.Seller.dashboard.index')

@section('title', 'صور المنتجات')
@section('page_title', 'صور المنتجات')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">
            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">قائمة صور المنتجات</h3>
                    <button id="add-image-btn" class="btn btn-success btn-sm">إضافة صورة جديدة</button>
                </div>

                <div class="form-content">
                    <form action="{{ route('seller.products.images.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="table-responsive" id="images-wrapper">
                            <table class="table table-bordered text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>المنتج</th>
                                        <th>الصورة</th>
                                        <th>النص البديل</th>
                                        <th>الترتيب</th>
                                        <th>أساسي</th>
                                        <th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($images as $image)
                                        <tr class="image-row" data-id="{{ $image->image_id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="hidden" name="images[{{ $image->image_id }}][image_id]"
                                                    value="{{ $image->image_id }}">
                                                <select name="images[{{ $image->image_id }}][product_id]"
                                                    class="form-control">
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->product_id }}"
                                                            {{ $product->product_id == $image->product_id ? 'selected' : '' }}>
                                                            {{ $product->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <img src="{{ asset($image->image_path) }}" width="80"
                                                    class="mb-1"><br>
                                                <input type="hidden" name="images[{{ $image->image_id }}][image_path]"
                                                    value="{{ $image->image_path }}">
                                                <input type="file" name="images[{{ $image->image_id }}][image]"
                                                    class="form-control mt-1">
                                            </td>

                                            <td><input type="text" name="images[{{ $image->image_id }}][alt_text]"
                                                    class="form-control" value="{{ $image->alt_text }}"></td>
                                            <td><input type="number" name="images[{{ $image->image_id }}][sort_order]"
                                                    class="form-control" value="{{ $image->sort_order }}"></td>
                                            <td>
                                                <select name="images[{{ $image->image_id }}][is_primary]"
                                                    class="form-control">
                                                    <option value="1" {{ $image->is_primary ? 'selected' : '' }}>نعم
                                                    </option>
                                                    <option value="0" {{ !$image->is_primary ? 'selected' : '' }}>لا
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-image"
                                                    data-id="{{ $image->image_id }}">حذف</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $images->links() }}
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_sdebar')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('images-wrapper');

            // حذف صورة باستخدام AJAX
            wrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-image')) {
                    const row = e.target.closest('.image-row');
                    const imageId = e.target.dataset.id;

                    if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                        fetch(`{{ url('seller/products/images') }}/${imageId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    row.remove();
                                    alert(data.success);
                                } else {
                                    alert(data.error || 'حدث خطأ أثناء الحذف');
                                }
                            });
                    }
                }
            });

            // إضافة صورة جديدة
            document.getElementById('add-image-btn').addEventListener('click', function() {
                const tbody = wrapper.querySelector('tbody');
                const index = Date.now();
                const row = document.createElement('tr');
                row.classList.add('image-row');
                row.innerHTML = `
            <td>جديد</td>
            <td>
                <select name="images[${index}][product_id]" class="form-control">
                    @foreach ($products as $product)
                        <option value="{{ $product->product_id }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="file" name="images[${index}][image]" class="form-control"></td>
            <td><input type="text" name="images[${index}][alt_text]" class="form-control" placeholder="النص البديل"></td>
            <td><input type="number" name="images[${index}][sort_order]" class="form-control" value="0"></td>
            <td>
                <select name="images[${index}][is_primary]" class="form-control">
                    <option value="1">نعم</option>
                    <option value="0" selected>لا</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger remove-image">حذف</button></td>
        `;
                tbody.appendChild(row);
            });
        });
    </script>
@endsection
