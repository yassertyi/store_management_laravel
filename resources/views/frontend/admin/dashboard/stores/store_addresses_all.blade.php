@extends('frontend.admin.dashboard.index')

@section('title', 'عناوين المتاجر')
@section('page_title', 'عناوين المتاجر')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        @if(session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة عناوين المتاجر</h3>
                <button id="add-address-btn" type="button" class="btn btn-success btn-sm">إضافة عنوان جديد</button>
            </div>

            <div class="form-content">
                <form action="{{ route('admin.stores.addresses.save') }}" method="POST">
                    @csrf
                    <div class="table-form table-responsive" id="addresses-wrapper">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المتجر</th>
                                    <th>الدولة</th>
                                    <th>المدينة</th>
                                    <th>الشارع</th>
                                    <th>الرمز البريدي</th>
                                    <th>أساسي</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addresses as $address)
                                    <tr class="address-row">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="hidden" name="addresses[{{ $address->address_id }}][address_id]" value="{{ $address->address_id }}">
                                            <input type="hidden" name="addresses[{{ $address->address_id }}][store_id]" value="{{ $address->store_id }}">
                                            {{ $address->store->store_name ?? 'غير محدد' }}
                                        </td>
                                        <td><input type="text" name="addresses[{{ $address->address_id }}][country]" class="form-control" value="{{ $address->country }}"></td>
                                        <td><input type="text" name="addresses[{{ $address->address_id }}][city]" class="form-control" value="{{ $address->city }}"></td>
                                        <td><input type="text" name="addresses[{{ $address->address_id }}][street]" class="form-control" value="{{ $address->street }}"></td>
                                        <td><input type="text" name="addresses[{{ $address->address_id }}][zip_code]" class="form-control" value="{{ $address->zip_code }}"></td>
                                        <td>
                                            <select name="addresses[{{ $address->address_id }}][is_primary]" class="form-control">
                                                <option value="1" {{ $address->is_primary ? 'selected' : '' }}>نعم</option>
                                                <option value="0" {{ !$address->is_primary ? 'selected' : '' }}>لا</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-address">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($addresses->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد بيانات</td>
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
            const addressId = row.querySelector('input[name*="[address_id]"]')?.value;

            if(addressId){
                if(confirm('هل أنت متأكد من حذف هذا العنوان؟')){
                    fetch(`{{ url('admin/stores/addresses') }}/${addressId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success){
                            row.remove();
                            alert(data.success);
                        } else {
                            alert(data.error || 'حدث خطأ أثناء الحذف');
                        }
                    })
                    .catch(() => alert('حدث خطأ أثناء الحذف'));
                }
            } else {
                // إذا كان العنوان جديد ولم يُحفظ بعد
                row.remove();
            }
        }
    });

    // إضافة عنوان جديد
    document.getElementById('add-address-btn').addEventListener('click', function(){
        const tbody = wrapper.querySelector('tbody');
        const index = Date.now(); // معرف مؤقت
        const row = document.createElement('tr');
        row.classList.add('address-row');
        row.innerHTML = `
            <td>جديد</td>
            <td>
                <select name="addresses[${index}][store_id]" class="form-control">
                    @foreach($stores as $store)
                        <option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="addresses[${index}][country]" class="form-control" placeholder="الدولة"></td>
            <td><input type="text" name="addresses[${index}][city]" class="form-control" placeholder="المدينة"></td>
            <td><input type="text" name="addresses[${index}][street]" class="form-control" placeholder="الشارع"></td>
            <td><input type="text" name="addresses[${index}][zip_code]" class="form-control" placeholder="الرمز البريدي"></td>
            <td>
                <select name="addresses[${index}][is_primary]" class="form-control">
                    <option value="1">نعم</option>
                    <option value="0" selected>لا</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger remove-address">حذف</button></td>
        `;
        tbody.appendChild(row);
    });

    // إخفاء الرسائل تلقائياً
    setTimeout(function() {
        const flash = document.getElementById('flash-message');
        if(flash){
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500); 
        }
    }, 3000);
});
</script>
@endsection
