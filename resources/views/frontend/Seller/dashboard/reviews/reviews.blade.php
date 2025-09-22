@extends('frontend.Seller.dashboard.index')

@section('title', 'التقييمات')
@section('page_title', 'إدارة التقييمات')

@section('contects')
<div class="dashboard-content-wrap">
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-box">
                        <div class="form-title-wrap d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="title">قائمة المراجعات</h3>
                                <p class="font-size-14">
                                    إظهار {{ $reviews->firstItem() }} إلى {{ $reviews->lastItem() }} من أصل {{ $reviews->total() }} مدخلات
                                </p>
                            </div>
                        </div>

                        <div class="form-content">
                            <div class="comments-list">
                                @foreach ($reviews as $review)
                                    <div class="comment d-flex align-items-start justify-content-between p-3 border rounded mb-3">
                                        <div class="comment-avatar me-3">
                                            @if ($review->user && $review->user->profile_photo)
                                                <img src="{{ asset($review->user->profile_photo) }}" alt="صورة {{ $review->user->name }}" class="rounded-circle avatar__img" width="50" height="50">
                                            @else
                                                <img src="{{ asset('static/images/Default_pfp.jpg') }}" alt="صورة افتراضية" class="rounded-circle avatar__img" width="50" height="50">
                                            @endif
                                        </div>

                                        <div class="comment-body flex-grow-1">
                                            <div class="meta-data d-flex align-items-center justify-content-between">
                                                <h3 class="comment__author mb-0 fw-bold">{{ $review->product->title ?? '---' }}</h3>
                                            </div>

                                            <div class="meta-data-inner d-flex align-items-center mt-1">
                                                <p class="comment__meta me-3 mb-0 text-primary fw-semibold">
                                                    بواسطة <a href="#">{{ $review->user->name ?? '---' }}</a>
                                                </p>

                                                <span class="ratings d-flex align-items-center me-3">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="la la-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </span>

                                                <p class="comment__date mb-0 text-muted">{{ $review->created_at->format('d M Y') }}</p>
                                            </div>

                                            <p class="comment-content mt-2">{{ $review->comment ?? '-' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="row">
                <div class="col-lg-12">
                    {{ $reviews->links() }}
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
        if (flash) {
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
</script>
@endsection
