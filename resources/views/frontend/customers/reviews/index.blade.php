@extends('frontend.customers.dashboard.index')

@section('title', 'تقييماتي')
@section('page_title', 'قائمة التقييمات')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">

            <div class="form-box">
                <div class="form-content">
                    <div class="comments-list">
                        @foreach ($reviews as $review)
                            <div class="comment d-flex align-items-start justify-content-between p-3 border rounded mb-3">

                                {{-- صورة المنتج --}}
                                <div class="comment-avatar me-3">
                                    @php
                                        $primaryImage = $review->product->images->where('is_primary', true)->first();
                                    @endphp

                                    @if ($primaryImage)
                                        <img src="{{ asset($primaryImage->image_path) }}" alt="{{ $review->product->title }}"
                                            class="rounded avatar__img" width="70" height="70">
                                    @elseif ($review->product && $review->product->photo)
                                        {{-- صورة المنتج الافتراضية القديمة --}}
                                        <img src="{{ asset($review->product->photo) }}" alt="{{ $review->product->title }}"
                                            class="rounded avatar__img" width="70" height="70">
                                    @else
                                        <img src="{{ asset('static/images/air-france.png') }}" alt="صورة افتراضية"
                                            class="rounded avatar__img" width="70" height="70">
                                    @endif
                                </div>


                                {{-- بيانات التقييم مع تفاصيل المنتج --}}
                                <div class="comment-body flex-grow-1">
                                    <div class="meta-data d-flex align-items-center justify-content-between">
                                        <h5 class="comment__author mb-1 fw-bold">{{ $review->product->title ?? '-' }}</h5>
                                    </div>

                                    <div class="product-details mb-2">
                                        <p class="mb-0 text-muted">
                                            السعر: {{ $review->product->price ?? '-' }} ريال
                                        </p>
                                        <p class="mb-0 text-muted">
                                            {{ Str::limit($review->product->description ?? '-', 80) }}
                                        </p>
                                    </div>

                                    <div class="meta-data-inner d-flex align-items-center mt-1">
                                        <span class="ratings d-flex align-items-center me-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="la la-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </span>

                                        <p class="comment__date mb-0 text-muted">{{ $review->created_at->format('d M Y') }}
                                        </p>
                                    </div>

                                    <p class="comment-content mt-2">{{ $review->comment ?? '-' }}</p>
                                </div>

                                {{-- زر الحذف --}}
                                <div class="comment-actions text-end ms-3">
                                    <button onclick="deleteReview({{ $review->review_id }})"
                                        class="btn btn-sm btn-outline-danger d-block mb-2">
                                        <i class="la la-trash me-1"></i> حذف
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script_sdebar')
    <script>
        function deleteReview(id) {
            if (confirm('هل أنت متأكد من حذف التقييم؟')) {
                fetch(`/customer/reviews/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) location.reload();
                    });
            }
        }
    </script>
@endsection
