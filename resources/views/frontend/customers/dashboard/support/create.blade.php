@extends('frontend.customers.dashboard.index')

@section('title', 'فتح تذكرة جديدة')
@section('page_title', 'فتح تذكرة جديدة')

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">فتح تذكرة جديدة</h3>
                </div>

                {{-- رسائل الأخطاء --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.support.tickets.store') }}" method="POST">
                    @csrf

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-life-ring me-2 text-gray"></i>بيانات التذكرة</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                            
                                <!-- الموضوع -->
                                <div class="col-lg-12 mb-3">
                                    <div class="input-box">
                                        <label class="label-text">الموضوع *</label>
                                        <input type="text" name="subject" class="form-control" 
                                               value="{{ old('subject') }}" required placeholder="ادخل موضوع التذكرة">
                                    </div>
                                </div>

                                <!-- الأولوية -->
                                <div class="col-lg-6 mb-3">
                                    <div class="input-box">
                                        <label class="label-text">الأولوية *</label>
                                        <select name="priority" class="form-control" required>
                                            <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>منخفضة</option>
                                            <option value="2" {{ old('priority') == 2 || !old('priority') ? 'selected' : '' }}>متوسطة</option>
                                            <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>عالية</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- الوصف -->
                                <div class="col-lg-12 mb-3">
                                    <div class="input-box">
                                        <label class="label-text">الوصف *</label>
                                        <textarea name="description" rows="5" class="form-control" required 
                                                  placeholder="اكتب وصف مفصل للمشكلة التي تواجهها">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- أزرار -->
                    <div class="submit-box mt-3">
                        <div class="btn-box pt-3 d-flex gap-2">
                            <button type="submit" class="theme-btn">
                                إرسال التذكرة <i class="la la-arrow-right ms-1"></i>
                            </button>
                            <a href="{{ route('customer.support.tickets.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection