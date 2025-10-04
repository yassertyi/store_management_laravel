{{-- resources/views/frontend/admin/content/settings/index.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', 'الإعدادات العامة')
@section('page_title', 'الإعدادات العامة')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">

            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">الإعدادات العامة</h3>
                    <a href="{{ route('admin.content.settings.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> إضافة إعداد جديد
                    </a>
                </div>

                <div class="form-content">
                    <form action="{{ route('admin.content.settings.bulk-update') }}" method="POST">
                        @csrf
                        
                        @php
                            $groupedSettings = $settings->groupBy('group_name');
                        @endphp

                        @foreach($groupedSettings as $groupName => $groupSettings)
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        @switch($groupName)
                                            @case('header')
                                                <i class="la la-header me-2"></i>إعدادات الهيدر
                                                @break
                                            @case('footer')
                                                <i class="la la-footer me-2"></i>إعدادات الفوتر
                                                @break
                                            @case('sections')
                                                <i class="la la-th-large me-2"></i>إعدادات الأقسام
                                                @break
                                            @case('services')
                                                <i class="la la-concierge-bell me-2"></i>إعدادات الخدمات
                                                @break
                                            @default
                                                <i class="la la-cog me-2"></i>{{ $groups[$groupName] ?? $groupName }}
                                        @endswitch
                                    </h5>
                                    <span class="badge bg-primary">{{ $groupSettings->count() }} إعداد</span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupSettings as $setting)
                                            <div class="col-lg-6 mb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <label for="setting_{{ $setting->key }}" class="form-label fw-bold">
                                                        {{ $setting->description ?? $setting->key }}
                                                    </label>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.content.settings.edit', $setting) }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="تعديل">
                                                            <i class="la la-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.content.settings.destroy', $setting) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعداد؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="حذف">
                                                                <i class="la la-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                
                                                @if($setting->type === 'textarea')
                                                    <textarea name="settings[{{ $setting->key }}]" 
                                                              id="setting_{{ $setting->key }}" 
                                                              class="form-control" 
                                                              rows="4">{{ $setting->value }}</textarea>
                                                @elseif($setting->type === 'number')
                                                    <input type="number" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           id="setting_{{ $setting->key }}" 
                                                           class="form-control" 
                                                           value="{{ $setting->value }}"
                                                           placeholder="أدخل قيمة رقمية">
                                                @elseif($setting->type === 'email')
                                                    <input type="email" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           id="setting_{{ $setting->key }}" 
                                                           class="form-control" 
                                                           value="{{ $setting->value }}"
                                                           placeholder="example@email.com">
                                                @elseif($setting->type === 'url')
                                                    <input type="url" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           id="setting_{{ $setting->key }}" 
                                                           class="form-control" 
                                                           value="{{ $setting->value }}"
                                                           placeholder="https://example.com">
                                                @else
                                                    <input type="text" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           id="setting_{{ $setting->key }}" 
                                                           class="form-control" 
                                                           value="{{ $setting->value }}"
                                                           placeholder="أدخل قيمة {{ $setting->description ?? $setting->key }}">
                                                @endif
                                                
                                                @if($setting->description)
                                                    <small class="form-text text-muted">{{ $setting->description }}</small>
                                                @endif
                                                
                                                <small class="form-text text-info">
                                                    <i class="la la-key"></i> المفتاح: <code>{{ $setting->key }}</code>
                                                    | <i class="la la-tag"></i> النوع: {{ $setting->type }}
                                                </small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($settings->isEmpty())
                            <div class="text-center py-5">
                                <i class="la la-cog la-4x text-muted mb-3"></i>
                                <h4 class="text-muted">لا توجد إعدادات</h4>
                                <p class="text-muted">ابدأ بإضافة إعدادات جديدة للموقع</p>
                                <a href="{{ route('admin.content.settings.create') }}" class="theme-btn theme-btn-small">
                                    <i class="la la-plus"></i> إضافة أول إعداد
                                </a>
                            </div>
                        @else
                            <div class="text-center mt-4">
                                <button type="submit" class="theme-btn theme-btn-large">
                                    <i class="la la-save me-2"></i>حفظ جميع الإعدادات
                                </button>
                            </div>
                        @endif
                    </form>
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