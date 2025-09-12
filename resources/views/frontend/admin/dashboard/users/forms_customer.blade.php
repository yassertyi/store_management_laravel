@extends('frontend.admin.dashboard.index')

@section('title', isset($customer) ? 'تعديل العميل' : 'إضافة عميل')
@section('page_title', 'العملاء')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="form-box">
                    <div class="form-title-wrap">
                        <h3 class="title">{{ isset($customer) ? 'تعديل العميل' : 'إضافة عميل جديد' }}</h3>
                    </div>
                    <div class="form-content">
                        <form action="{{ isset($customer) ? route('admin.customers.update', $customer->customer_id) : route('admin.customers.store') }}" method="POST">
                            @csrf
                            @if(isset($customer)) @method('PUT') @endif

                            <div class="mb-3">
                                <label for="user_id" class="form-label">اختر المستخدم</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">-- اختر المستخدم --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}" 
                                            {{ isset($customer) && $customer->user_id == $user->user_id ? 'selected' : '' }}>
                                            {{ $user->name }} - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="loyalty_points" class="form-label">نقاط الولاء</label>
                                <input type="number" name="loyalty_points" id="loyalty_points" class="form-control" 
                                       value="{{ $customer->loyalty_points ?? old('loyalty_points',0) }}">
                            </div>

                            <div class="mb-3">
                                <label for="total_orders" class="form-label">عدد الطلبات</label>
                                <input type="number" name="total_orders" id="total_orders" class="form-control" 
                                       value="{{ $customer->total_orders ?? old('total_orders',0) }}">
                            </div>

                            <button type="submit" class="theme-btn theme-btn-small">
                                {{ isset($customer) ? 'تحديث' : 'إضافة' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
