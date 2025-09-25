@extends('frontend.customers.dashboard.index')

@section('title', 'تفاصيل التذكرة')
@section('page_title', 'تفاصيل التذكرة')

@section('css_sdebar')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --user-msg-bg: #e3f2fd;
    --seller-msg-bg: #f0f7ff;
    --border-radius: 12px;
    --box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

body { font-family: 'Tajawal', sans-serif; background-color: #f5f7f9; }

.ticket-header-box {
    background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
    color: white; padding: 20px; border-radius: var(--border-radius);
    margin-bottom: 20px; box-shadow: var(--box-shadow);
}

.status-badge { padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
.status-open { background: rgba(255,255,255,0.2); color: white; }
.status-review { background: #facc15; color: #212529; }
.status-closed { background: #ff6b6b; color: white; }

.chat-container {
    background: white; border-radius: var(--border-radius); overflow: hidden;
    box-shadow: var(--box-shadow); height: 500px; display: flex; flex-direction: column;
}

.chat-header { padding: 15px 20px; border-bottom: 1px solid #eee; background: #f8f9fa; }

.chat-messages { flex: 1; padding: 20px; overflow-y: auto; background: #f9fafb; }

.message { display: flex; margin-bottom: 20px; width: 100%; }
.message-content-wrapper { max-width: 70%; display: flex; flex-direction: column; }

.user-message { justify-content: flex-start; }
.seller-message { justify-content: flex-end; margin-left: auto; }

.message-sender { display: flex; align-items: center; margin-bottom: 6px; }
.sender-avatar { width: 30px; height: 30px; border-radius: 50%; margin-right: 8px; object-fit: cover; }
.sender-name { font-size: 0.85rem; font-weight: 500; }

.message-content {
    padding: 12px 16px; border-radius: var(--border-radius);
    box-shadow: 0 2px 5px rgba(0,0,0,0.05); word-wrap: break-word;
}

.user-message .message-content { background: var(--user-msg-bg); border-radius: 12px 12px 12px 4px; }
.seller-message .message-content { background: var(--seller-msg-bg); border-radius: 12px 12px 4px 12px; color: #212529; }

.timestamp { font-size: 0.7rem; margin-top: 4px; opacity: 0.7; align-self: flex-start; }
.seller-message .timestamp { align-self: flex-end; }

.chat-footer { padding: 20px; background: white; border-top: 1px solid #eee; }
.message-form { display: flex; gap: 10px; }
.message-input { flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 24px; outline: none; font-size: 1rem; resize: none; transition: border-color 0.3s; }
.message-input:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(67,97,238,0.15); }
.send-button { background: var(--primary-color); color: white; border: none; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.3s; }
.send-button:hover { background: var(--secondary-color); }

.chat-messages::-webkit-scrollbar { width: 6px; }
.chat-messages::-webkit-scrollbar-track { background: #f1f1f1; }
.chat-messages::-webkit-scrollbar-thumb { background: #c5c5c5; border-radius: 10px; }
</style>
@endsection

@section('contects')
<div class="dashboard-main-content">
    <br><br>
    <div class="container-fluid py-4">

        <!-- معلومات التذكرة -->
        <div class="ticket-header-box">
            <h3>موضوع التذكرة: {{ $ticket->subject ?? '-' }}</h3>
            <div class="d-flex flex-column flex-md-row justify-content-between">
                <div>
                    <p class="mb-1"><strong>الوصف:</strong> {{ $ticket->description }}</p>
                    <p class="mb-0"><strong>الأولوية:</strong> 
                        @if($ticket->priority==3) عالية
                        @elseif($ticket->priority==2) متوسطة
                        @else منخفضة
                        @endif
                    </p>
                </div>
                <div class="mt-2 mt-md-0">
                    <p class="mb-0"><strong>الحالة:</strong>
                        @if($ticket->status==0)
                            <span class="status-badge status-open">مفتوحة</span>
                        @elseif($ticket->status==1)
                            <span class="status-badge status-review">قيد المراجعة</span>
                        @else
                            <span class="status-badge status-closed">مغلقة</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- صندوق الرسائل -->
        <div class="chat-container">
            <div class="chat-header">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i> الرسائل</h5>
            </div>

            <div id="chatMessages" class="chat-messages">
                @foreach($messages as $message)
                @php
                    $sender = $message->user;
                    $senderName = $sender ? $sender->name : 'Admin';
                    $senderPhoto = $sender && $sender->profile_photo && file_exists(public_path('static/images/users/'.$sender->profile_photo))
                                   ? asset('static/images/users/'.$sender->profile_photo)
                                   : asset('static/images/avatar.jpeg');
                @endphp

                <div class="message {{ $message->user_id == auth()->id() ? 'user-message' : 'seller-message' }}">
                    <div class="message-content-wrapper">
                        <div class="message-sender">
                            <img src="{{ $senderPhoto }}" alt="{{ $senderName }}" class="sender-avatar">
                            <span class="sender-name">{{ $senderName }}</span>
                        </div>
                        <div class="message-content">{{ $message->message }}</div>
                        <div class="timestamp">{{ $message->created_at->format('H:i | Y-m-d') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- في قسم النموذج - تصحيح المسار -->
@if($ticket->status != 2)
<div class="chat-footer">
    <form action="{{ route('customer.support.tickets.message', $ticket->ticket_id) }}" method="POST" class="message-form">
        @csrf
        <textarea name="message" class="message-input" rows="1" placeholder="اكتب رسالتك هنا..." required></textarea>
        <button class="send-button" type="submit"><i class="fas fa-paper-plane"></i></button>
    </form>
</div>
@endif

<!-- زر إغلاق التذكرة -->
@if($ticket->status != 2)
<form action="{{ route('customer.support.tickets.close', $ticket->ticket_id) }}" method="POST" class="mt-2">
    @csrf
    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من إغلاق التذكرة؟')">
        <i class="la la-times me-1"></i> إغلاق التذكرة
    </button>
</form>
@endif
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
    // تحديث تلقائي للرسائل (Ajax)
function loadMessages() {
    $.ajax({
        url: '{{ route('customer.support.tickets.show', $ticket->ticket_id) }}',
        type: 'GET',
        success: function(data) {
            $('#chatMessages').html($(data).find('#chatMessages').html());
            scrollToBottom();
        }
    });
}

// تحديث كل 10 ثواني
setInterval(loadMessages, 10000);

// إرسال رسالة بدون إعادة تحميل الصفحة
$('.message-form').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function() {
            $('.message-input').val('');
            loadMessages();
        }
    });
});
    // تمرير تلقائي للأسفل
    const chatContainer = document.getElementById('chatMessages');
    if(chatContainer){
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // توسيع حقل النص تلقائي
    const textarea = document.querySelector('.message-input');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight)+'px';
    });
</script>
@endsection
