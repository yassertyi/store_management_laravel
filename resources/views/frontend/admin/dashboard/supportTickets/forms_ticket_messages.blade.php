@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($message) ? 'تعديل الرسالة' : 'إضافة رسالة جديدة' }}
@endsection

@section('page_title')
إدارة رسائل التذاكر - {{ isset($message) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($message) ? 'تعديل الرسالة' : 'إضافة رسالة جديدة' }}</h3>
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

                <form action="{{ isset($message) ? route('admin.ticket-messages.update', $message->message_id) : route('admin.ticket-messages.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($message))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-envelope me-2 text-gray"></i>معلومات الرسالة</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <!-- التذكرة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">التذكرة *</label>
                                        <div class="form-group">
                                            <select name="ticket_id" class="form-control" required>
                                                <option value="">اختر التذكرة</option>
                                                @foreach($tickets as $ticket)
                                                    <option value="{{ $ticket->ticket_id }}" 
                                                        {{ old('ticket_id', $message->ticket_id ?? '') == $ticket->ticket_id ? 'selected' : '' }}>
                                                        #{{ $ticket->ticket_id }} - {{ $ticket->subject }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- المستخدم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المستخدم *</label>
                                        <div class="form-group">
                                            <select name="user_id" class="form-control" required>
                                                <option value="">اختر المستخدم</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->user_id }}" 
                                                        {{ old('user_id', $message->user_id ?? '') == $user->user_id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- محتوى الرسالة -->
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الرسالة *</label>
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" rows="4" required placeholder="أدخل الرسالة هنا">{{ old('message', $message->message ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- حالة القراءة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group">
                                            <select name="is_read" class="form-control">
                                                <option value="0" {{ old('is_read', $message->is_read ?? 0) == 0 ? 'selected' : '' }}>غير مقروءة</option>
                                                <option value="1" {{ old('is_read', $message->is_read ?? 0) == 1 ? 'selected' : '' }}>مقروءة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="submit-box pt-3">
                        <button type="submit" class="theme-btn">{{ isset($message) ? 'تحديث الرسالة' : 'حفظ الرسالة' }} <i class="la la-arrow-right ms-1"></i></button>
                        <a href="{{ route('admin.ticket-messages.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
