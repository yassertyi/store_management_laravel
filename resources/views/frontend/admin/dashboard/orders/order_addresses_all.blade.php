@extends('frontend.admin.dashboard.index')

@section('title', 'عناوين الطلبات')
@section('page_title', 'عناوين الطلبات')

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
                <h3 class="title">قائمة عناوين الطلبات</h3>
                <button id="add-address-btn" type="button" class="btn btn-success btn-sm">إضافة عنوان جديد</button>
            </div>

            <div class="form-content">
                <form action="{{ route('admin.orders.addresses.save') }}" method="POST">
                    @csrf
                    <div class="table-form table-responsive" id="addresses-wrapper">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الطلب</th>
                                    <th>نوع العنوان</th>
                                    <th>الاسم الأول</th>
                                    <th>الاسم الأخير</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الهاتف</th>
                                    <th>الدولة</th>
                                    <th>المدينة</th>
                                    <th>الشارع</th>
                                    <th>الرمز البريدي</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addresses as $addr)
                                    <tr class="address-row">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="hidden" name="addresses[{{ $addr->order_address_id }}][order_address_id]" value="{{ $addr->order_address_id }}">
                                            <select name="addresses[{{ $addr->order_address_id }}][order_id]" class="form-control">
                                                @foreach(\App\Models\Order::all() as $order)
                                                    <option value="{{ $order->order_id }}" {{ $addr->order_id == $order->order_id ? 'selected' : '' }}>
                                                        {{ $order->order_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="addresses[{{ $addr->order_address_id }}][address_type]" class="form-control">
                                                <option value="billing" {{ $addr->address_type=='billing' ? 'selected':'' }}>فاتورة</option>
                                                <option value="shipping" {{ $addr->address_type=='shipping' ? 'selected':'' }}>شحن</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][first_name]" value="{{ $addr->first_name }}" class="form-control"></td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][last_name]" value="{{ $addr->last_name }}" class="form-control"></td>
                                        <td><input type="email" name="addresses[{{ $addr->order_address_id }}][email]" value="{{ $addr->email }}" class="form-control"></td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][phone]" value="{{ $addr->phone }}" class="form-control"></td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][country]" value="{{ $addr->country }}" class="form-control"></td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][city]" value="{{ $addr->city }}" class="form-control"></td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][street]" value="{{ $addr->street }}" class="form-control"></td>
                                        <td><input type="text" name="addresses[{{ $addr->order_address_id }}][zip_code]" value="{{ $addr->zip_code }}" class="form-control"></td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-address" data-id="{{ $addr->order_address_id }}">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($addresses->isEmpty())
                                    <tr>
                                        <td colspan="12" class="text-center">لا توجد بيانات</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $addresses->links() }}
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
    const wrapper = document.getElementById('addresses-wrapper');

    // حذف عنوان
    wrapper.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-address')){
            const row = e.target.closest('.address-row');
            const addrId = e.target.dataset.id;

            if(addrId){
                if(confirm('هل تريد حذف هذا العنوان؟')){
                    fetch(`{{ url('admin/orders/addresses') }}/${addrId}`, {
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

    // إضافة عنوان جديد
    document.getElementById('add-address-btn').addEventListener('click', function(){
        const tbody = wrapper.querySelector('tbody');
        const index = Date.now();
        const row = document.createElement('tr');
        row.classList.add('address-row');
        row.innerHTML = `
            <td>جديد</td>
            <td>
                <select name="addresses[${index}][order_id]" class="form-control">
                    @foreach(\App\Models\Order::all() as $order)
                        <option value="{{ $order->order_id }}">{{ $order->order_number }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="addresses[${index}][address_type]" class="form-control">
                    <option value="billing">فاتورة</option>
                    <option value="shipping">شحن</option>
                </select>
            </td>
            <td><input type="text" name="addresses[${index}][first_name]" class="form-control"></td>
            <td><input type="text" name="addresses[${index}][last_name]" class="form-control"></td>
            <td><input type="email" name="addresses[${index}][email]" class="form-control"></td>
            <td><input type="text" name="addresses[${index}][phone]" class="form-control"></td>
            <td><input type="text" name="addresses[${index}][country]" class="form-control"></td>
            <td><input type="text" name="addresses[${index}][city]" class="form-control"></td>
            <td><input type="text" name="addresses[${index}][street]" class="form-control"></td>
            <td><input type="text" name="addresses[${index}][zip_code]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-address">حذف</button></td>
        `;
        tbody.appendChild(row);
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
