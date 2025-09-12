@extends('frontend.admin.dashboard.index')

@section('title', isset($order) ? 'تعديل الطلب' : 'إضافة طلب جديد')
@section('page_title', 'إدارة الطلبات - ' . (isset($order) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($order) ? 'تعديل الطلب' : 'إضافة طلب جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($order) ? route('admin.orders.update', $order->order_id) : route('admin.orders.store') }}" method="POST">
                    @csrf
                    @if (isset($order))
                        @method('PUT')
                    @endif

                    <!-- معلومات الطلب -->
                    <div class="form-box mb-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-shopping-cart me-2 text-gray"></i>معلومات الطلب</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">العميل *</label>
                                        <select name="customer_id" class="form-control" required>
                                            <option value="">اختر العميل</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->customer_id }}"
                                                    {{ old('customer_id', $order->customer_id ?? '') == $customer->customer_id ? 'selected' : '' }}>
                                                    {{ $customer->user->name ?? $customer->customer_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">رقم الطلب *</label>
                                        <input type="text" name="order_number" class="form-control"
                                            value="{{ old('order_number', $order->order_number ?? 'ORD-' . time()) }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">المجموع الفرعي *</label>
                                        <input type="number" step="0.01" id="subtotal" name="subtotal" class="form-control"
                                            value="{{ old('subtotal', $order->subtotal ?? 0) }}" readonly required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">الضريبة</label>
                                        <input type="number" step="0.01" id="tax_amount" name="tax_amount" class="form-control"
                                            value="{{ old('tax_amount', $order->tax_amount ?? 0) }}">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">الشحن</label>
                                        <input type="number" step="0.01" id="shipping_amount" name="shipping_amount" class="form-control"
                                            value="{{ old('shipping_amount', $order->shipping_amount ?? 0) }}">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">الخصم</label>
                                        <input type="number" step="0.01" id="discount_amount" name="discount_amount" class="form-control"
                                            value="{{ old('discount_amount', $order->discount_amount ?? 0) }}">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">الإجمالي *</label>
                                        <input type="number" step="0.01" id="total_amount" name="total_amount" class="form-control"
                                            value="{{ old('total_amount', $order->total_amount ?? 0) }}" readonly required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">حالة الطلب *</label>
                                        <select name="status" class="form-control" required>
                                            <option value="">اختر الحالة</option>
                                            <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                            <option value="processing" {{ old('status', $order->status ?? '') == 'processing' ? 'selected' : '' }}>جارٍ التنفيذ</option>
                                            <option value="completed" {{ old('status', $order->status ?? '') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                            <option value="cancelled" {{ old('status', $order->status ?? '') == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">حالة الدفع *</label>
                                        <select name="payment_status" class="form-control" required>
                                            <option value="">اختر الحالة</option>
                                            <option value="pending" {{ old('payment_status', $order->payment_status ?? '') == 'pending' ? 'selected' : '' }}>غير مدفوع</option>
                                            <option value="paid" {{ old('payment_status', $order->payment_status ?? '') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                            <option value="failed" {{ old('payment_status', $order->payment_status ?? '') == 'failed' ? 'selected' : '' }}>فشل الدفع</option>
                                            <option value="refunded" {{ old('payment_status', $order->payment_status ?? '') == 'refunded' ? 'selected' : '' }}>تم استرداده</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">طريقة الدفع</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="">اختر الطريقة</option>
                                            <option value="cash" {{ old('payment_method', $order->payment_method ?? '') == 'cash' ? 'selected' : '' }}>كاش</option>
                                            <option value="visa" {{ old('payment_method', $order->payment_method ?? '') == 'visa' ? 'selected' : '' }}>فيزا / ماستر كارد</option>
                                            <option value="bank" {{ old('payment_method', $order->payment_method ?? '') == 'bank' ? 'selected' : '' }}>تحويل بنكي</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-box">
                                        <label class="label-text">تاريخ الطلب</label>
                                        <input type="date" name="order_date" class="form-control"
                                            value="{{ old('order_date', isset($order->order_date) ? $order->order_date->format('Y-m-d') : date('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="input-box">
                                        <label class="label-text">ملاحظات</label>
                                        <textarea name="notes" class="form-control">{{ old('notes', $order->notes ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- عناصر الطلب -->
                    <div class="form-box mb-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-list me-2 text-gray"></i>عناصر الطلب</h3>
                            <small class="text-muted">اختياري: يمكنك ترك هذا القسم فارغًا.</small>
                        </div>
                        <div class="form-content contact-form-action">
                            <div id="items-wrapper">
                                @php
                                    $items = old('items', isset($order) ? $order->items->toArray() : [['product_id' => '', 'variant_id'=>'', 'quantity' => 1, 'unit_price' => 0, 'total_price'=>0]]);
                                @endphp
                                @foreach ($items as $i => $item)
                                    <div class="row item-row mb-2">
                                        <div class="col-lg-4">
                                            <select name="items[{{ $i }}][product_id]" class="form-control product-select" data-index="{{ $i }}">
                                                <option value="">اختر المنتج</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->product_id }}"
                                                        data-price="{{ $product->price }}"
                                                        {{ old("items.$i.product_id", $item['product_id'] ?? '') == $product->product_id ? 'selected' : '' }}>
                                                        {{ $product->title }} - {{ $product->price }} ريال
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="number" name="items[{{ $i }}][quantity]" class="form-control quantity-input" value="{{ $item['quantity'] ?? 1 }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="number" step="0.01" name="items[{{ $i }}][unit_price]" class="form-control price-input" value="{{ $item['unit_price'] ?? 0 }}" readonly>
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="number" step="0.01" name="items[{{ $i }}][total_price]" class="form-control total-price-input" value="{{ $item['total_price'] ?? 0 }}" readonly>
                                        </div>
                                        <div class="col-lg-1">
                                            <button type="button" class="btn {{ $loop->first ? 'btn-success add-item' : 'btn-danger remove-item' }}">{{ $loop->first ? '+' : '-' }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- عناوين الطلب -->
                    <div class="form-box mb-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-map-marker me-2 text-gray"></i>عناوين الطلب</h3>
                            <small class="text-muted">اختياري: يمكنك ترك هذا القسم فارغًا.</small>
                        </div>
                        <div class="form-content contact-form-action">
                            <div id="addresses-wrapper">
                                @php
                                    $addresses = old('addresses', isset($order) ? $order->addresses->toArray() : [['address_type' => 'shipping','first_name' => '', 'last_name' => '', 'email'=>'','phone'=>'','country'=>'','city'=>'','street'=>'','zip_code'=>'']]);
                                @endphp
                                @foreach ($addresses as $j => $addr)
                                    <div class="row address-row mb-2">
                                        <div class="col-lg-2">
                                            <select name="addresses[{{ $j }}][address_type]" class="form-control">
                                                <option value="shipping" {{ ($addr['address_type'] ?? '') == 'shipping' ? 'selected' : '' }}>شحن</option>
                                                <option value="billing" {{ ($addr['address_type'] ?? '') == 'billing' ? 'selected' : '' }}>فاتورة</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $j }}][first_name]" class="form-control" placeholder="الاسم الأول" value="{{ $addr['first_name'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $j }}][last_name]" class="form-control" placeholder="اسم العائلة" value="{{ $addr['last_name'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="email" name="addresses[{{ $j }}][email]" class="form-control" placeholder="البريد الإلكتروني" value="{{ $addr['email'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $j }}][phone]" class="form-control" placeholder="الهاتف" value="{{ $addr['phone'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $j }}][country]" class="form-control" placeholder="الدولة" value="{{ $addr['country'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2 mt-2">
                                            <input type="text" name="addresses[{{ $j }}][city]" class="form-control" placeholder="المدينة" value="{{ $addr['city'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2 mt-2">
                                            <input type="text" name="addresses[{{ $j }}][street]" class="form-control" placeholder="الشارع" value="{{ $addr['street'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2 mt-2">
                                            <input type="text" name="addresses[{{ $j }}][zip_code]" class="form-control" placeholder="الرمز البريدي" value="{{ $addr['zip_code'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-1 mt-2">
                                            <button type="button" class="btn {{ $loop->first ? 'btn-success add-address' : 'btn-danger remove-address' }}">{{ $loop->first ? '+' : '-' }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($order) ? 'تحديث الطلب' : 'حفظ الطلب' }}</button>
                        <a href="{{ route('admin.orders.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // === حساب الإجماليات (موجود عندك) ===
    function calculateTotals() {
        document.querySelectorAll('.item-row').forEach(row => {
            let qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            let price = parseFloat(row.querySelector('.price-input').value) || 0;
            row.querySelector('.total-price-input').value = (qty * price).toFixed(2);
        });

        let subtotal = 0;
        document.querySelectorAll('.total-price-input').forEach(el => subtotal += parseFloat(el.value) || 0);

        document.getElementById('subtotal').value = subtotal.toFixed(2);

        let tax = parseFloat(document.getElementById('tax_amount').value) || 0;
        let shipping = parseFloat(document.getElementById('shipping_amount').value) || 0;
        let discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        let total = subtotal + tax + shipping - discount;
        document.getElementById('total_amount').value = total.toFixed(2);
    }

    // === إضافة عنصر جديد ===
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('add-item')){
            let wrapper = document.getElementById('items-wrapper');
            let rows = wrapper.querySelectorAll('.item-row');
            let newIndex = rows.length;

            let newRow = rows[0].cloneNode(true);

            // تعديل أسماء الحقول للعنصر الجديد
            newRow.querySelectorAll('input, select').forEach(el => {
                el.name = el.name.replace(/\d+/, newIndex);
                if(el.tagName === 'SELECT'){
                    el.selectedIndex = 0;
                } else {
                    el.value = (el.classList.contains('quantity-input')) ? 1 : 0;
                }
            });

            // تغيير زر من + إلى -
            let btn = newRow.querySelector('button');
            btn.classList.remove('btn-success','add-item');
            btn.classList.add('btn-danger','remove-item');
            btn.textContent = '-';

            wrapper.appendChild(newRow);
        }

        if(e.target.classList.contains('remove-item')){
            e.target.closest('.item-row').remove();
            calculateTotals();
        }

        if(e.target.classList.contains('add-address')){
            let wrapper = document.getElementById('addresses-wrapper');
            let rows = wrapper.querySelectorAll('.address-row');
            let newIndex = rows.length;

            let newRow = rows[0].cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(el => {
                el.name = el.name.replace(/\d+/, newIndex);
                el.value = '';
            });

            let btn = newRow.querySelector('button');
            btn.classList.remove('btn-success','add-address');
            btn.classList.add('btn-danger','remove-address');
            btn.textContent = '-';

            wrapper.appendChild(newRow);
        }

        if(e.target.classList.contains('remove-address')){
            e.target.closest('.address-row').remove();
        }
    });

    // === تحديث الحسابات عند التغيير ===
    document.addEventListener('change', function(e){
        if(e.target.classList.contains('product-select')){
            let price = e.target.options[e.target.selectedIndex].dataset.price;
            e.target.closest('.item-row').querySelector('.price-input').value = price || 0;
            calculateTotals();
        }
    });

    document.addEventListener('input', function(e){
        if(e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')){
            calculateTotals();
        }
    });

    document.querySelectorAll('#tax_amount, #shipping_amount, #discount_amount').forEach(el => el.addEventListener('input', calculateTotals));

    calculateTotals();
});
</script>

@endsection
