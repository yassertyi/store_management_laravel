@extends('frontend.admin.dashboard.index')

@section('title', 'عناوين العملاء')
@section('page_title', 'إدارة عناوين العملاء')

@section('contects')

<br> <br> <br>
<div class="dashboard-main-content">
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة عناوين العملاء</h3>
                            <p class="font-size-14">
                                إظهار {{ $addresses->firstItem() }} إلى {{ $addresses->lastItem() }} من أصل {{ $addresses->total() }} مدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.customer-addresses.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> عنوان جديد
                            </a>
                        </div>
                    </div>

                    <div class="form-content table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم العميل</th>
                                    <th>العنوان الكامل</th>
                                    <th>الهاتف</th>
                                    <th>البلد</th>
                                    <th>الحالة الافتراضية</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addresses as $address)
                                <tr>
                                    <td>{{ $address->address_id }}</td>
                                    <td>{{ $address->user->name ?? '-' }}</td>
                                    <td>
                                        {{ $address->title ?? '' }} {{ $address->first_name ?? '' }} {{ $address->last_name ?? '' }},
                                        {{ $address->street ?? '' }} - {{ $address->apartment ?? '' }}, {{ $address->city ?? '' }}, {{ $address->zip_code ?? '' }}
                                    </td>
                                    <td>{{ $address->phone ?? '-' }}</td>
                                    <td>{{ $address->country ?? '-' }}</td>
                                    <td>
                                        @if($address->is_default)
                                            <span class="badge text-bg-success">افتراضي</span>
                                        @else
                                            <span class="badge text-bg-secondary">غير افتراضي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customer-addresses.edit', $address->address_id) }}" class="btn btn-primary btn-sm mb-1">تعديل</a>
                                        <button class="btn btn-danger btn-sm mb-1 delete-address" data-id="{{ $address->address_id }}">حذف</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $addresses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-address').forEach(button => {
    button.addEventListener('click', function() {
        if(confirm('هل أنت متأكد من حذف هذا العنوان؟')) {
            const id = this.dataset.id;
            fetch(`/admin/customer-addresses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            })
            .then(res => res.json())
            .then(res => {
                alert(res.success);
                location.reload();
            });
        }
    });
});
</script>
@endsection
