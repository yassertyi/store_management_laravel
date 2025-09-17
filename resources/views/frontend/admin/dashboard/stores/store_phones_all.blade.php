@extends('frontend.admin.dashboard.index')

@section('title', 'اتصالات المتاجر')
@section('page_title', 'اتصالات المتاجر')

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

        @if(session('error'))
            <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة اتصالات المتاجر</h3>
                <button id="add-phone-btn" class="btn btn-success btn-sm">إضافة رقم جديد</button>
            </div>

            <div class="form-content">
                <form action="{{ route('admin.stores.phones.save') }}" method="POST">
                    @csrf
                    <div class="table-form table-responsive" id="phones-wrapper">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المتجر</th>
                                    <th>رقم الهاتف</th>
                                    <th>أساسي</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($phones as $phone)
                                    <tr class="phone-row">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="hidden" name="phones[{{ $phone->phone_id }}][phone_id]" value="{{ $phone->phone_id }}">
                                            <input type="hidden" name="phones[{{ $phone->phone_id }}][store_id]" value="{{ $phone->store_id }}">
                                            {{ $phone->store->store_name ?? 'غير محدد' }}
                                        </td>
                                        <td>
                                            <input type="text" name="phones[{{ $phone->phone_id }}][number]" class="form-control" value="{{ $phone->phone }}">
                                        </td>
                                        <td>
                                            <select name="phones[{{ $phone->phone_id }}][is_primary]" class="form-control">
                                                <option value="1" {{ $phone->is_primary ? 'selected' : '' }}>نعم</option>
                                                <option value="0" {{ !$phone->is_primary ? 'selected' : '' }}>لا</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-phone" data-id="{{ $phone->phone_id }}">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($phones->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">لا توجد بيانات</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $phones->links() }}
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
    const wrapper = document.getElementById('phones-wrapper');

    // حذف رقم هاتف باستخدام AJAX
    wrapper.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-phone')){
            const row = e.target.closest('.phone-row');
            const phoneId = e.target.dataset.id;

            if(phoneId){
                if(confirm('هل أنت متأكد من حذف هذا الرقم؟')){
                    fetch(`{{ url('admin/stores/phones') }}/${phoneId}`, {
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
                // إذا كان الرقم جديد ولم يُحفظ بعد
                row.remove();
            }
        }
    });

    // إضافة رقم جديد
    document.getElementById('add-phone-btn').addEventListener('click', function(){
        const tbody = wrapper.querySelector('tbody');
        const index = Date.now(); // معرف مؤقت
        const row = document.createElement('tr');
        row.classList.add('phone-row');
        row.innerHTML = `
            <td>جديد</td>
            <td>
                <select name="phones[${index}][store_id]" class="form-control">
                    @foreach($stores as $store)
                        <option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="phones[${index}][number]" class="form-control" placeholder="رقم الهاتف">
            </td>
            <td>
                <select name="phones[${index}][is_primary]" class="form-control">
                    <option value="1">نعم</option>
                    <option value="0" selected>لا</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-phone">حذف</button>
            </td>
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
