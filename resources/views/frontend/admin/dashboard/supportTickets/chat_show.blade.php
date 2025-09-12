@extends('frontend.admin.dashboard.index')

@section('title', 'عرض المحادثة')
@section('page_title', 'تفاصيل المحادثة')

@section('css_sdebar')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --user-msg-bg: #e3f2fd;
        --admin-msg-bg: #f0f7ff;
        --border-radius: 12px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Tajawal', sans-serif;
        background-color: #f5f7f9;
    }

    .chat-header-box {
        background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        box-shadow: var(--box-shadow);
    }

    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-open { background: rgba(255, 255, 255, 0.2); color: white; }
    .status-closed { background: #ff6b6b; color: white; }

    .chat-container {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
        height: 600px;
        display: flex;
        flex-direction: column;
    }

    .chat-header { padding: 15px 20px; border-bottom: 1px solid #eee; background: #f8f9fa; }

    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f9fafb;
    }

    .message {
        display: flex;
        margin-bottom: 20px;
        width: 100%;
    }

    .message-content-wrapper { max-width: 70%; display: flex; flex-direction: column; }

    .user-message { justify-content: flex-start; }
    .admin-message { justify-content: flex-end; margin-left: auto; }

    .message-sender { display: flex; align-items: center; margin-bottom: 6px; }
    .sender-avatar { width: 30px; height: 30px; border-radius: 50%; margin-right: 8px; object-fit: cover; }
    .sender-name { font-size: 0.85rem; font-weight: 500; }

    .message-content {
        padding: 12px 16px;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        word-wrap: break-word;
    }

    .user-message .message-content { background: var(--user-msg-bg); border-radius: 12px 12px 12px 4px; }
    .admin-message .message-content { background: var(--admin-msg-bg); border-radius: 12px 12px 4px 12px; color: #212529; }

    .timestamp { font-size: 0.7rem; margin-top: 4px; opacity: 0.7; align-self: flex-start; }
    .admin-message .timestamp { align-self: flex-end; }

    .chat-footer { padding: 20px; background: white; border-top: 1px solid #eee; }
    .message-form { display: flex; gap: 10px; }
    .message-input { flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 24px; outline: none; font-size: 1rem; resize: none; transition: border-color 0.3s; }
    .message-input:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15); }

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
        <!-- معلومات المحادثة -->
        <div class="chat-header-box">
            <h3>موضوع المحادثة: {{ $chat->subject ?? '-' }}</h3>
            <div class="d-flex flex-column flex-md-row justify-content-between">
                <div>
                    <p class="mb-1"><i class="fas fa-user me-2"></i> <strong>المستخدم:</strong> {{ $chat->user->name ?? 'ضيف' }}</p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i> <strong>تاريخ البدء:</strong> {{ $chat->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="mt-2 mt-md-0">
                    <p class="mb-0"><strong>الحالة:</strong>
                        @if($chat->status == 'open')
                            <span class="status-badge status-open"><i class="fas fa-circle me-1"></i> مفتوحة</span>
                        @else
                            <span class="status-badge status-closed"><i class="fas fa-circle me-1"></i> مغلقة</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- صندوق المحادثة -->
        <div class="chat-container">
            <div class="chat-header">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i> الرسائل</h5>
            </div>

            <div id="chatMessages" class="chat-messages">
                @foreach($chat->messages as $msg)
    @php
        $sender = $msg->sender;
        $senderName = $sender ? $sender->name : 'Admin';

        if($sender && $sender->profile_photo && file_exists(public_path('static/images/users/' . $sender->profile_photo))){
            $senderPhoto = asset('static/images/users/' . $sender->profile_photo);
        } else {
            $senderPhoto = asset('static/images/avatar.jpeg');
        }
    @endphp

    <div class="message {{ $msg->sender_id == $chat->user->user_id ? 'user-message' : 'admin-message' }}">
        <div class="message-content-wrapper">
            <div class="message-sender">
                <img src="{{ $senderPhoto }}" alt="{{ $senderName }}" class="sender-avatar">
                <span class="sender-name">{{ $senderName }}</span>
            </div>
            <div class="message-content">{{ $msg->content }}</div>
            <div class="timestamp">{{ $msg->created_at->format('H:i | Y-m-d') }}</div>
        </div>
    </div>
@endforeach

            </div>

            <!-- نموذج إرسال الرسالة -->
            <div class="chat-footer">
                <form action="{{ route('admin.chats.store') }}" method="POST" class="message-form">
                    @csrf
                    <input type="hidden" name="chat_id" value="{{ $chat->chat_id }}">
                    <textarea name="content" class="message-input" rows="1" placeholder="اكتب رسالتك هنا..." required></textarea>
                    <button class="send-button" type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // تمرير تلقائي لأسفل عند تحميل الصفحة
    const chatContainer = document.getElementById('chatMessages');
    if(chatContainer){
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // توسيع حقل النص تلقائياً عند الكتابة
    const textarea = document.querySelector('.message-input');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endsection
