@extends('frontend.customers.dashboard.index')

@section('title', 'عناويني')
@section('page_title', 'إدارة العناوين')

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
                <h3 class="title">قائمة عناويني</h3>
                <a href="{{ route('customer.addresses.create') }}" class="theme-btn theme-btn-small">
                    <i class="la la-plus"></i> إضافة عنوان جديد
                </a>
            </div>

            <div class="form-content">
                <div class="table-form table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>المدينة</th>
                                <th>الدولة</th>
                                <th>الهاتف</th>
                                <th>التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addresses as $address)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $address->title }} - {{ $address->street }}</td>
                                    <td>{{ $address->city }}</td>
                                    <td>{{ $address->country }}</td>
                                    <td>{{ $address->phone }}</td>
                                    <td>
                                        <a href="{{ route('customer.addresses.edit', $address) }}" class="theme-btn theme-btn-small me-2">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="theme-btn theme-btn-small bg-danger">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">لا توجد عناوين بعد</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $addresses->links() }}
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
    if(flash){
        flash.style.transition = "opacity 0.5s ease";
        flash.style.opacity = '0';
        setTimeout(() => flash.remove(), 500);
    }
}, 3000);
</script>
@endsection