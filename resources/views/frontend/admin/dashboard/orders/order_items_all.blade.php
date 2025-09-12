@extends('frontend.admin.dashboard.index')

@section('title', 'عناصر الطلبات')
@section('page_title', 'عناصر الطلبات')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        @if(session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة عناصر الطلبات</h3>
                <button id="add-item-btn" type="button" class="btn btn-success btn-sm">إضافة عنصر جديد</button>
            </div>

            <div class="form-content">
                <form action="{{ route('admin.orders.items.save') }}" method="POST">
                    @csrf
                    <div class="table-form table-responsive" id="items-wrapper">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الطلب</th>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الإجمالي</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="item-row">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="hidden" name="items[{{ $item->item_id }}][item_id]" value="{{ $item->item_id }}">
                                            <select name="items[{{ $item->item_id }}][order_id]" class="form-control">
                                                @foreach($orders as $order)
                                                    <option value="{{ $order->order_id }}" {{ $item->order_id == $order->order_id ? 'selected' : '' }}>
                                                        {{ $order->order_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="items[{{ $item->item_id }}][product_id]" class="form-control">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->product_id }}" data-price="{{ $product->price }}" {{ $item->product_id == $product->product_id ? 'selected' : '' }}>
                                                        {{ $product->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="items[{{ $item->item_id }}][quantity]" value="{{ $item->quantity }}" class="form-control"></td>
                                        <td><input type="text" name="items[{{ $item->item_id }}][unit_price]" value="{{ $item->unit_price }}" class="form-control unit-price" readonly></td>
                                        <td>{{ $item->total_price }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-item" data-id="{{ $item->item_id }}">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($items->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد بيانات</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $items->links() }}
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
    const wrapper = document.getElementById('items-wrapper');

    // دالة لتحديث سعر الوحدة والإجمالي عند اختيار المنتج
    function updateUnitPrice(row) {
        const productSelect = row.querySelector('select[name*="[product_id]"]');
        const unitPriceInput = row.querySelector('.unit-price');
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const totalCell = row.querySelector('td:last-child');

        const price = parseFloat(productSelect.selectedOptions[0].dataset.price || 0);
        unitPriceInput.value = price;
        totalCell.textContent = price * (parseFloat(quantityInput.value) || 1);
    }

    // حذف عنصر طلب
    wrapper.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-item')){
            const row = e.target.closest('.item-row');
            const itemId = e.target.dataset.id;

            if(itemId){
                if(confirm('هل تريد حذف هذا العنصر؟')){
                    fetch(`{{ url('admin/orders/items') }}/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            row.remove();
                            alert(data.success);
                        } else {
                            alert(data.error || 'خطأ أثناء الحذف');
                        }
                    })
                    .catch(() => alert('خطأ أثناء الحذف'));
                }
            } else {
                row.remove();
            }
        }
    });

    // إضافة عنصر جديد
    document.getElementById('add-item-btn').addEventListener('click', function(){
        const tbody = wrapper.querySelector('tbody');
        const index = Date.now();
        const row = document.createElement('tr');
        row.classList.add('item-row');
        row.innerHTML = `
            <td>جديد</td>
            <td>
                <select name="items[${index}][order_id]" class="form-control">
                    @foreach($orders as $order)
                        <option value="{{ $order->order_id }}">{{ $order->order_number }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="items[${index}][product_id]" class="form-control">
                    @foreach($products as $product)
                        <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][quantity]" class="form-control" value="1"></td>
            <td><input type="text" name="items[${index}][unit_price]" class="form-control unit-price" value="0" readonly></td>
            <td>0</td>
            <td><button type="button" class="btn btn-danger remove-item">حذف</button></td>
        `;
        tbody.appendChild(row);
        updateUnitPrice(row);
    });

    // تحديث السعر عند اختيار المنتج
    wrapper.addEventListener('change', function(e){
        if(e.target.name.includes('[product_id]')){
            const row = e.target.closest('.item-row');
            updateUnitPrice(row);
        }
    });

    // تحديث الإجمالي عند تغيير الكمية
    wrapper.addEventListener('input', function(e){
        if(e.target.name.includes('[quantity]')){
            const row = e.target.closest('.item-row');
            const unitPrice = parseFloat(row.querySelector('.unit-price').value || 0);
            const quantity = parseFloat(e.target.value || 0);
            row.querySelector('td:last-child').textContent = unitPrice * quantity;
        }
    });

    // اخفاء الرسائل بعد 3 ثواني
    setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if(flash){
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
});
</script>
@endsection
