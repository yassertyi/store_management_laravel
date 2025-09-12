@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($message) ? 'تعديل الرسالة' : 'إنشاء رسالة جديدة' }}
@endsection

@section('page_title')
إدارة الرسائل - {{ isset($message) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($message) ? 'تعديل الرسالة' : 'إنشاء رسالة جديدة' }}</h3>
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

                <form action="{{ isset($message) ? route('admin.messages.update', ['message' => $message->message_id]) : route('admin.messages.store') }}" 
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
                                <!-- المرسل -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المرسل *</label>
                                        <div class="form-group">
                                            <select name="sender_id" class="form-control" required>
                                                <option value="">اختر المرسل</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->user_id }}" 
    {{ old('sender_id', $message->sender_id ?? '') == $user->user_id ? 'selected' : '' }}>
    {{ $user->name }}
</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- المستلم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المستلم *</label>
                                        <div class="form-group">
                                            <select name="receiver_id" class="form-control" required>
                                                <option value="">اختر المستلم</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->user_id }}" 
    {{ old('receiver_id', $message->receiver_id ?? '') == $user->user_id ? 'selected' : '' }}>
    {{ $user->name }}
</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
<div class="form-group">
    <label for="chat_id">اختر المحادثة</label>
    <select name="chat_id" id="chat_id" class="form-control" required>
        @foreach($chats as $chat)
            <option value="{{ $chat->chat_id }}">
                محادثة رقم {{ $chat->chat_id }}
            </option>
        @endforeach
    </select>
</div>

                               <!-- المحتوى -->
<div class="col-lg-12 responsive-column">
    <div class="input-box">
        <label class="label-text">المحتوى *</label>
        <div class="form-group">
            <textarea name="content" class="form-control" rows="5" required placeholder="نص الرسالة">{{ old('content', $message->content ?? '') }}</textarea>
        </div>
    </div>
</div>

                                <!-- حالة القراءة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group">
                                            <select name="is_read" class="form-control">
                                                <option value="0" {{ old('is_read', $message->is_read ?? '') == 0 ? 'selected' : '' }}>غير مقروءة</option>
                                                <option value="1" {{ old('is_read', $message->is_read ?? '') == 1 ? 'selected' : '' }}>مقروءة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="submit-box pt-3">
                        <button type="submit" class="theme-btn">{{ isset($message) ? 'تحديث الرسالة' : 'حفظ الرسالة' }} <i class="la la-arrow-right ms-1"></i></button>
                        <a href="{{ route('admin.messages.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
