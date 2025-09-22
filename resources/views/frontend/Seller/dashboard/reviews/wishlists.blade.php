@extends('frontend.Seller.dashboard.index')

@section('title', 'قائمة المفضلة')
@section('page_title', 'إدارة المفضلة')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <h3 class="title">قائمة المفضلة</h3>
                    </div>

                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div id="flash-message" class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="form-content pb-2">
                        @foreach ($wishlists as $wishlist)
                            <div class="card-item card-item-list">
                                <div class="card-img">
                                    <img src="{{ $wishlist->product->images->first() ? asset($wishlist->product->images->first()->image_path) : asset('images/default.png') }}"
                                         alt="{{ $wishlist->product->title }}" class="img-fluid" />
                                </div>

                                <div class="card-body">
                                    <p class="card-meta pb-1">{{ $wishlist->product->category->name ?? 'منتج' }}</p>
                                    <h3 class="card-title">{{ $wishlist->product->title }}</h3>
                                    <p class="card-meta pt-2 pb-3">
                                        {{ Str::limit($wishlist->product->description, 100, '...') }}
                                    </p>
                                    <div class="btn-box">
                                        <a href="{{ route('seller.products.show', $wishlist->product->product_id) }}"
                                           class="theme-btn theme-btn-small theme-btn-transparent">
                                           <i class="la la-eye me-1"></i>عرض المنتج
                                        </a>
                                    </div>
                                </div>
                                <div class="action-btns">
                                    <form action="{{ route('seller.seller.wishlists.destroy', $wishlist->wishlist_id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إزالة هذا المنتج من المفضلة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="theme-btn theme-btn-small bg-danger">
                                            <i class="la la-times me-1"></i>إلغاء
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $wishlists->links() }}
                    </div>
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
