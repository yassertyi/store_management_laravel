@extends('frontend.admin.dashboard.index')

@section('title')
    {{ isset($payment) ? 'تعديل الدفع' : 'إضافة دفع جديد' }}
@endsection

@section('page_title')
    إدارة المدفوعات - {{ isset($payment) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($payment) ? 'تعديل الدفع' : 'إضافة دفع جديد' }}</h3>
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

                <form action="{{ isset($payment) ? route('admin.payments.update', $payment->payment_id) : route('admin.payments.store') }}" method="POST">
                    @csrf
                    @if(isset($payment))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-credit-card me-2 text-gray"></i>معلومات الدفع</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">

                                <!-- الطلب -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الطلب *</label>
                                        <select id="orderSelect" class="form-control" name="order_id" required>
                                            <option value="">اختر الطلب</option>
                                            @foreach($orders as $order)
                                                <option value="{{ $order->order_id }}" 
                                                    data-amount="{{ $order->subtotal }}" 
                                                    data-discount="{{ $order->discount_amount }}" 
                                                    data-total="{{ $order->total_amount }}" 
                                                    data-currency="{{ $order->currency ?? 'USD' }}"
                                                    {{ old('order_id', $payment->order_id ?? '') == $order->order_id ? 'selected' : '' }}>
                                                    {{ $order->order_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- طريقة الدفع -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">طريقة الدفع *</label>
                                        <select class="form-control" name="store_payment_method_id" id="paymentMethodSelect" required>
                                            <option value="">اختر الطريقة</option>
                                            @foreach($methods as $method)
                                                <option value="{{ $method->spm_id }}" 
                                                    data-method="{{ $method->account_name }}"
                                                    {{ old('store_payment_method_id', $payment->store_payment_method_id ?? '') == $method->spm_id ? 'selected' : '' }}>
                                                    {{ $method->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- المبلغ -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المبلغ *</label>
                                        <input id="amountField" class="form-control" type="number" name="amount" 
                                               value="{{ old('amount', $payment->amount ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- الخصم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الخصم</label>
                                        <input id="discountField" class="form-control" type="number" name="discount" 
                                               value="{{ old('discount', $payment->discount ?? 0) }}">
                                    </div>
                                </div>

                                <!-- المبلغ الكلي -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المبلغ الكلي *</label>
                                        <input id="totalField" class="form-control" type="number" name="total_amount" 
                                               value="{{ old('total_amount', $payment->total_amount ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- العملة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">العملة *</label>
                                        <input id="currencyField" class="form-control" type="text" name="currency" 
                                               value="{{ old('currency', $payment->currency ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- نوع الدفع -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">نوع الدفع *</label>
                                        <select class="form-control" name="type" required>
                                            <option value="">اختر النوع</option>
                                            <option value="online" {{ old('type', $payment->type ?? '') == 'online' ? 'selected' : '' }}>أونلاين</option>
                                            <option value="cash" {{ old('type', $payment->type ?? '') == 'cash' ? 'selected' : '' }}>كاش</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة *</label>
                                        <select class="form-control" name="status" required>
                                            <option value="">اختر الحالة</option>
                                            <option value="pending" {{ old('status', $payment->status ?? '') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                            <option value="completed" {{ old('status', $payment->status ?? '') == 'completed' ? 'selected' : '' }}>مدفوع</option>
                                            <option value="failed" {{ old('status', $payment->status ?? '') == 'failed' ? 'selected' : '' }}>ملغي</option>
                                            <option value="refunded" {{ old('status', $payment->status ?? '') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- رقم العملية -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">رقم العملية</label>
                                        <input class="form-control" type="text" name="transaction_id" 
                                               value="{{ old('transaction_id', $payment->transaction_id ?? '') }}">
                                    </div>
                                </div>

                                <!-- تاريخ الدفع -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">تاريخ الدفع *</label>
                                        <input class="form-control" type="date" name="payment_date" 
                                               value="{{ old('payment_date', isset($payment) && $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : '') }}" required>
                                    </div>
                                </div>

                                <!-- طريقة الدفع النصية (method) -->
                                <input type="hidden" name="method" id="methodField" value="{{ old('method', $payment->method ?? '') }}">

                                <!-- ملاحظة -->
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">ملاحظة</label>
                                        <textarea class="form-control" name="note">{{ old('note', $payment->note ?? '') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="submit-box pt-3">
                        <button type="submit" class="theme-btn">{{ isset($payment) ? 'تحديث الدفع' : 'حفظ الدفع' }} <i class="la la-arrow-right ms-1"></i></button>
                        <a href="{{ route('admin.payments.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('orderSelect').addEventListener('change', function() {
    let option = this.options[this.selectedIndex];
    document.getElementById('amountField').value = option.getAttribute('data-amount') || '';
    document.getElementById('discountField').value = option.getAttribute('data-discount') || '';
    document.getElementById('totalField').value = option.getAttribute('data-total') || '';
    document.getElementById('currencyField').value = option.getAttribute('data-currency') || '';
});

// تحديث حقل method عند اختيار طريقة الدفع
document.getElementById('paymentMethodSelect').addEventListener('change', function() {
    let option = this.options[this.selectedIndex];
    document.getElementById('methodField').value = option.getAttribute('data-method') || '';
});
</script>
@endsection
