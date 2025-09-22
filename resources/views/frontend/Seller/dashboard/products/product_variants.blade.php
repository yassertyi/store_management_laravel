@extends('frontend.Seller.dashboard.index')

@section('title', 'متغيرات المنتجات')
@section('page_title', 'متغيرات المنتجات')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">
            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">قائمة متغيرات المنتجات</h3>
                    <button id="add-variant-btn" type="button" class="btn btn-success btn-sm">إضافة متغير جديد</button>
                </div>

                <div class="form-content">
                    <form action="{{ route('seller.products.variants.save') }}" method="POST">
                        @csrf
                        <div class="table-responsive" id="variants-wrapper">
                            <table class="table table-bordered text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>المنتج</th>
                                        <th>اسم المتغير</th>
                                        <th>السعر</th>
                                        <th>المخزون</th>
                                        <th>SKU</th>
                                        <th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variants as $variant)
                                        <tr class="variant-row" data-id="{{ $variant->variant_id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <select name="variants[{{ $variant->variant_id }}][product_id]" class="form-control" required>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->product_id }}" @if($product->product_id == $variant->product_id) selected @endif>
                                                            {{ $product->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="variants[{{ $variant->variant_id }}][name]" class="form-control" value="{{ $variant->name }}">
                                                <input type="hidden" name="variants[{{ $variant->variant_id }}][variant_id]" value="{{ $variant->variant_id }}">
                                            </td>
                                            <td><input type="number" step="0.01" name="variants[{{ $variant->variant_id }}][price]" class="form-control" value="{{ $variant->price }}"></td>
                                            <td><input type="number" name="variants[{{ $variant->variant_id }}][stock]" class="form-control" value="{{ $variant->stock }}"></td>
                                            <td><input type="text" name="variants[{{ $variant->variant_id }}][sku]" class="form-control" value="{{ $variant->sku }}" readonly></td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-variant" data-id="{{ $variant->variant_id }}">حذف</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $variants->links() }}
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
        const wrapper = document.getElementById('variants-wrapper');

        // حذف متغير باستخدام AJAX
        wrapper.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variant')) {
                const row = e.target.closest('.variant-row');
                const variantId = e.target.dataset.id;

                if (confirm('هل أنت متأكد من حذف هذا المتغير؟')) {
                    fetch(`{{ url('seller/products/variants') }}/${variantId}`, {
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

        // دالة توليد SKU عشوائي
        function generateSku() {
            return 'SKU' + Math.floor(Math.random() * 1000000);
        }

        // إضافة متغير جديد
        document.getElementById('add-variant-btn').addEventListener('click', function() {
            const tbody = wrapper.querySelector('tbody');
            const index = Date.now();
            const row = document.createElement('tr');
            row.classList.add('variant-row');
            row.innerHTML = `
                <td>جديد</td>
                <td>
                    <select name="variants[${index}][product_id]" class="form-control">
                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}">{{ $product->title }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="variants[${index}][name]" class="form-control" placeholder="اسم المتغير"></td>
                <td><input type="number" step="0.01" name="variants[${index}][price]" class="form-control" value="0"></td>
                <td><input type="number" name="variants[${index}][stock]" class="form-control" value="0"></td>
                <td><input type="text" name="variants[${index}][sku]" class="form-control" value="${generateSku()}" readonly></td>
                <td><button type="button" class="btn btn-danger remove-variant">حذف</button></td>
            `;
            tbody.appendChild(row);
        });
    });
</script>
@endsection
