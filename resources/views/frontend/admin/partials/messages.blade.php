<div class="notification-item me-2">
    <div class="dropdown">
        @php
            $userType = Auth::user()->user_type ?? null;
            // عداد الرسائل غير المقروءة
            $unreadMessagesCount = $unreadMessagesCount ?? 0;
        @endphp

        <a href="#" class="dropdown-toggle drop-reveal-toggle-icon" 
           id="messageDropdownMenu" data-bs-toggle="dropdown" 
           aria-haspopup="true" aria-expanded="false" 
           onclick="markMessagesRead()">
            <i class="la la-envelope"></i>
            <span class="noti-count" id="message-count">{{ $unreadMessagesCount }}</span>
        </a>

        <div class="dropdown-menu dropdown-reveal dropdown-menu-xl dropdown-menu-right">
            <div class="dropdown-header drop-reveal-header">
                <h6 class="title">لديك <strong class="text-black">{{ $unreadMessagesCount }}</strong> رسائل</h6>
            </div>

            <div class="list-group drop-reveal-list">
                @forelse($unreadMessages as $message)
                    <a href="
                        @if($userType == 2) {{ route('admin.chats.show', $message->chat_id) }}
                        @elseif($userType == 1) {{ route('seller.seller.support.show', $message->chat_id) }}
                        @elseif($userType == 0) {{ route('customer.support.tickets.show', $message->chat_id) }}
                        @endif
                    " class="list-group-item list-group-item-action">
                        <div class="msg-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                @if($message->sender->profile_photo)
                                    <img src="{{ asset($message->sender->profile_photo) }}" 
                                         alt="صورة {{ $message->sender->name }}" 
                                         class="rounded-circle me-2" width="40" height="40">
                                @else
                                    <img src="{{ asset('static/images/Default_pfp.jpg') }}" 
                                         alt="صورة افتراضية" 
                                         class="rounded-circle me-2" width="40" height="40">
                                @endif
                            </div>
                            <div class="msg-content w-100">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="title pb-1">{{ $message->sender->name }}</h3>
                                    <span class="msg-text">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="msg-text">{{ \Illuminate\Support\Str::limit($message->content, 50) }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-center p-2">لا توجد رسائل جديدة</p>
                @endforelse
            </div>

            <a href="
                @if($userType == 2) {{ route('admin.messages.index') }}
                @elseif($userType == 1) {{ route('seller.notifications.index') }}
                @elseif($userType == 0) {{ route('customer.notifications.index') }}
                @endif
            " class="dropdown-item drop-reveal-btn text-center">مشاهدة الكل</a>
        </div>
    </div>
</div>

<script>
function markMessagesRead() {
    let route = '';
    @if($userType == 2)
        route = "{{ route('admin.messages.markRead') }}";
    @elseif($userType == 1)
        route = "{{ route('seller.seller.notifications.markAllRead') }}";
    @elseif($userType == 0)
        route = "{{ route('customer.notifications.markAllRead') }}";
    @endif

    fetch(route, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById('message-count').textContent = 0;
        }
    });
}
</script>
