@extends('frontend.Seller.dashboard.index')

@section('title', 'تصويتات التقييمات')
@section('page_title', 'إدارة تصويتات التقييمات')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة تصويتات التقييمات</h3>
                            <p class="font-size-14">
                                إظهار {{ $helpfuls->firstItem() }} إلى {{ $helpfuls->lastItem() }} من أصل {{ $helpfuls->total() }} تصويت
                            </p>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>رقم</th>
                                        <th>التقييم</th>
                                        <th>المستخدم</th>
                                        <th>مفيد؟</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($helpfuls as $helpful)
                                        <tr>
                                            <td>{{ $helpful->helpful_id }}</td>
                                            <td>{{ $helpful->review->title ?? '---' }}</td>
                                            <td>{{ $helpful->user->name ?? '---' }}</td>
                                            <td>
                                                @if($helpful->is_helpful)
                                                    <span class="badge text-bg-success">نعم</span>
                                                @else
                                                    <span class="badge text-bg-danger">لا</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $helpfuls->links() }}
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
