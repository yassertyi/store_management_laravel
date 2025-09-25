@extends('frontend.customers.dashboard.index')

@section('title', 'قائمة المفضلة')
@section('page_title', 'قائمة المفضلة')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <h3 class="title">قائمة المفضلة</h3>
                        <div>
                            <span class="badge bg-primary">
                                <i class="la la-heart me-1"></i> {{ $wishlists->total() }} منتج
                            </span>
                        </div>
                    </div>

                    {{-- Flash Messages --}}
                    <div id="flash-container"></div>

                    <div class="form-content pb-2">
                        @if($wishlists->count() > 0)
                            <div class="row">
                                @foreach ($wishlists as $wishlist)
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card wishlist-card h-100">
                                            <div class="card-img position-relative">
                                                <img src="{{ $wishlist->product->images->first() ? asset($wishlist->product->images->first()->image_path) : asset('images/default.png') }}"
                                                    alt="{{ $wishlist->product->title }}" 
                                                    class="img-fluid product-image" />

                                                {{-- زر الإزالة --}}
                                                <button class="wishlist-remove-btn" 
        onclick="removeFromWishlist(event, {{ $wishlist->wishlist_id }})"
        data-url="{{ route('customer.wishlist.destroy', $wishlist->wishlist_id) }}"
        title="إزالة من المفضلة">
    <i class="la la-heart"></i>
</button>

                                            </div>

                                            <div class="card-body d-flex flex-column">
                                                <p class="card-meta text-muted small mb-2">
                                                    {{ $wishlist->product->category->name ?? 'منتج' }}
                                                </p>
                                                <h5 class="card-title mb-2">{{ $wishlist->product->title }}</h5>

                                                <div class="price-section mb-3">
                                                    <span class="price text-primary fw-bold">{{ number_format($wishlist->product->price, 2) }} ريال</span>
                                                    @if($wishlist->product->compare_price && $wishlist->product->compare_price > $wishlist->product->price)
                                                        <span class="compare-price text-muted text-decoration-line-through ms-2">
                                                            {{ number_format($wishlist->product->compare_price, 2) }} ريال
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="card-text text-muted small flex-grow-1">
                                                    {{ Str::limit($wishlist->product->description, 80, '...') }}
                                                </p>

                                                <div class="mt-auto">
                                                    <div class="d-grid gap-2">
                                                        <a href=""
                                                            class="btn btn-primary btn-sm">
                                                            <i class="la la-shopping-cart me-1"></i>عرض المنتج
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="la la-heart la-3x text-muted"></i>
                                </div>
                                <h4 class="text-muted">قائمة المفضلة فارغة</h4>
                                <p class="text-muted">لم تقم بإضافة أي منتجات إلى المفضلة بعد</p>
                                <a href="" class="btn btn-primary mt-3">
                                    <i class="la la-shopping-bag me-1"></i>تصفح المنتجات
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if($wishlists->hasPages())
                        <div class="mt-4">
                            {{ $wishlists->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
function removeFromWishlist(event, wishlistId) {
    event.preventDefault();
    const button = event.target.closest('.wishlist-remove-btn');
    const url = button.getAttribute('data-url');

    button.innerHTML = '<i class="la la-spinner la-spin"></i>';
    button.disabled = true;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const card = button.closest('.col-lg-4, .col-md-6');
            card.remove();
            updateWishlistCount();
            showFlashMessage(data.message, 'success');
        } else {
            button.innerHTML = '<i class="la la-heart"></i>';
            button.disabled = false;
            showFlashMessage(data.error || 'حدث خطأ', 'error');
        }
    })
    .catch(() => {
        button.innerHTML = '<i class="la la-heart"></i>';
        button.disabled = false;
        showFlashMessage('حدث خطأ أثناء الإزالة', 'error');
    });
}

function updateWishlistCount() {
    const badge = document.querySelector('.badge.bg-primary');
    const cards = document.querySelectorAll('.wishlist-card');
    const count = cards.length;
    if(badge) {
        badge.innerHTML = `<i class="la la-heart me-1"></i> ${count} منتج`;
    }
    if(count === 0) showEmptyState();
}

function showEmptyState() {
    const formContent = document.querySelector('.form-content');
    formContent.innerHTML = `
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon mb-3">
                <i class="la la-heart la-3x text-muted"></i>
            </div>
            <h4 class="text-muted">قائمة المفضلة فارغة</h4>
            <p class="text-muted">لم تقم بإضافة أي منتجات إلى المفضلة بعد</p>
            <a href="" class="btn btn-primary mt-3">
                <i class="la la-shopping-bag me-1"></i>تصفح المنتجات
            </a>
        </div>
    `;
}

function showFlashMessage(message, type = 'success') {
    const flashContainer = document.getElementById('flash-container');
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'la-check-circle' : 'la-exclamation-circle';

    const flashMessage = document.createElement('div');
    flashMessage.className = `alert ${alertClass} alert-dismissible fade show mt-2`;
    flashMessage.innerHTML = `<i class="la ${iconClass} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    flashContainer.appendChild(flashMessage);

    setTimeout(() => {
        flashMessage.style.transition = "opacity 0.5s ease";
        flashMessage.style.opacity = '0';
        setTimeout(() => flashMessage.remove(), 500);
    }, 3000);
}
</script>


@endsection

@section('css_sdebar')

<style>
.wishlist-card { border:1px solid #e9ecef; border-radius:12px; overflow:hidden; transition:all 0.3s ease; }
.card-img { position:relative; height:200px; overflow:hidden; }
.product-image { width:100%; height:100%; object-fit:cover; transition: transform 0.3s ease; }
.wishlist-card:hover .product-image { transform:scale(1.05); }
.wishlist-remove-btn { position:absolute; top:10px; left:10px; background:rgba(255,255,255,0.9); border:none; border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; color:#dc3545; font-size:18px; cursor:pointer; z-index:10; transition:all 0.3s ease; }
.wishlist-remove-btn:hover { background:#dc3545; color:white; transform:scale(1.1); }
.price-section { display:flex; align-items:center; }
.price { font-size:1.25rem; }
.compare-price { font-size:0.9rem; text-decoration:line-through; margin-left:0.5rem; }
.empty-state { padding:3rem 1rem; text-align:center; }
.btn-primary { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); border:none; border-radius:8px; padding:0.5rem 1rem; }
.btn-primary:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(102,126,234,0.3); }
@media(max-width:768px){ .card-img{height:150px;} .wishlist-remove-btn{width:35px;height:35px;font-size:16px;} }
</style>
@endsection