@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($ticket) ? 'تعديل التذكرة' : 'إنشاء تذكرة جديدة' }}
@endsection

@section('page_title')
إدارة التذاكر - {{ isset($ticket) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($ticket) ? 'تعديل التذكرة' : 'إنشاء تذكرة جديدة' }}</h3>
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

                <form action="{{ isset($ticket) ? route('admin.support-tickets.update', $ticket->ticket_id) : route('admin.support-tickets.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($ticket))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-ticket me-2 text-gray"></i>معلومات التذكرة</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <!-- المستخدم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المستخدم *</label>
                                        <div class="form-group">
                                            <select name="customer_id" class="form-control" required>
                                                <option value="">اختر المستخدم</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->customer_id }}" 
                                                        {{ old('customer_id', $ticket->customer_id ?? '') == $customer->customer_id ? 'selected' : '' }}>
                                                        {{ optional($customer->user)->name ?? 'غير محدد' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- الموضوع -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الموضوع *</label>
                                        <div class="form-group">
                                            <input type="text" name="subject" class="form-control" 
                                                   value="{{ old('subject', $ticket->subject ?? '') }}" required placeholder="موضوع التذكرة">
                                        </div>
                                    </div>
                                </div>

                                <!-- الوصف -->
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الوصف *</label>
                                        <div class="form-group">
                                            <textarea name="description" class="form-control" rows="4" required placeholder="وصف التذكرة">{{ old('description', $ticket->description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة *</label>
                                        <div class="form-group">
                                            <select name="status" class="form-control" required>
                                                <option value="0" {{ old('status', $ticket->status ?? '') == 0 ? 'selected' : '' }}>مفتوحة</option>
                                                <option value="1" {{ old('status', $ticket->status ?? '') == 1 ? 'selected' : '' }}>قيد التنفيذ</option>
                                                <option value="2" {{ old('status', $ticket->status ?? '') == 2 ? 'selected' : '' }}>مغلقة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- الأولوية -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الأولوية *</label>
                                        <div class="form-group">
                                            <select name="priority" class="form-control" required>
                                                <option value="0" {{ old('priority', $ticket->priority ?? '') == 0 ? 'selected' : '' }}>عالية</option>
                                                <option value="1" {{ old('priority', $ticket->priority ?? '') == 1 ? 'selected' : '' }}>متوسطة</option>
                                                <option value="2" {{ old('priority', $ticket->priority ?? '') == 2 ? 'selected' : '' }}>منخفضة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- المسؤول -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المسؤول</label>
                                        <div class="form-group">
                                            <select name="assigned_to" class="form-control">
                                                <option value="">غير محدد</option>
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->user_id }}" 
                                                        {{ old('assigned_to', $ticket->assigned_to ?? '') == $admin->user_id ? 'selected' : '' }}>
                                                        {{ $admin->name ?? 'غير محدد' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="submit-box pt-3">
                        <button type="submit" class="theme-btn">{{ isset($ticket) ? 'تحديث التذكرة' : 'حفظ التذكرة' }} <i class="la la-arrow-right ms-1"></i></button>
                        <a href="{{ route('admin.support-tickets.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
