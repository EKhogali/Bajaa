@extends('layout.master')

@section('content')
<style>
    .home-hero {
        background-image: linear-gradient(rgba(255,255,255,.90), rgba(255,255,255,.90)), url('{{ asset('images/bg02.jpg') }}');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        border-radius: 16px;
        padding: 32px 28px;
        margin: -8px -8px 0;
    }

    .branch-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 18px;
        margin-top: 24px;
    }

    .branch-card {
        position: relative;
        display: block;
        text-decoration: none;
        background: #fff;
        border: 1px solid #e7eaf0;
        border-top: 4px solid var(--accent, #2c3e50);
        border-radius: 14px;
        padding: 22px 18px 18px;
        text-align: center;
        transition: transform .18s ease, box-shadow .18s ease;
        box-shadow: 0 1px 3px rgba(20, 30, 60, .05);
        overflow: hidden;
    }

    .branch-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 26px rgba(20, 30, 60, .14);
    }

    .branch-card:hover .branch-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    .branch-avatar {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        border-radius: 16px;
        background: var(--accent, #2c3e50);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .branch-name {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .branch-year-badge {
        display: inline-block;
        font-size: 12px;
        font-weight: 600;
        color: var(--accent, #2c3e50);
        background: var(--accent-tint, #eef2f6);
        border-radius: 20px;
        padding: 3px 12px;
    }

    .branch-arrow {
        position: absolute;
        bottom: 14px;
        left: 14px;
        opacity: 0;
        transform: translateX(6px);
        transition: opacity .18s ease, transform .18s ease;
        color: var(--accent, #2c3e50);
    }

    .branch-card.disabled {
        opacity: .5;
        pointer-events: none;
        cursor: not-allowed;
        border-top-color: #b0b6bd !important;
    }

    .branch-card.disabled .branch-avatar {
        background: #b0b6bd !important;
    }

    .page-title {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 14px;
    }
</style>

<div class="text-right home-hero" dir="rtl">
    <h1 class="page-title">المحاسبة الرقمية | Digital Accounting</h1>
    <div class="page-subtitle">اختر الفرع للمتابعة</div>

    @php
        $palette = [
            ['accent' => '#2563eb', 'tint' => '#e8f0fe'],
            ['accent' => '#059669', 'tint' => '#e4f5ee'],
            ['accent' => '#d97706', 'tint' => '#fdf1e0'],
            ['accent' => '#7c3aed', 'tint' => '#f0eafd'],
            ['accent' => '#dc2626', 'tint' => '#fbe9e9'],
            ['accent' => '#0891b2', 'tint' => '#e3f5f9'],
        ];
    @endphp

    <div class="branch-grid">
        @foreach($companies as $company)
            @php
                $fy = $financial_years->where('company_id', $company->id)->first();
                $colors = $palette[$loop->index % count($palette)];
            @endphp

            @if($fy)
                <a href="/company_and_financial_year?financial_year_id={{ $fy->id }}&company_id={{ $company->id }}"
                   class="branch-card" style="--accent: {{ $colors['accent'] }}; --accent-tint: {{ $colors['tint'] }};">
                    <div class="branch-avatar">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9.5L4.2 4H19.8L21 9.5" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 9.5C3 10.9 4.1 12 5.5 12C6.9 12 8 10.9 8 9.5C8 10.9 9.1 12 10.5 12C11.9 12 13 10.9 13 9.5C13 10.9 14.1 12 15.5 12C16.9 12 18 10.9 18 9.5C18 10.9 19.1 12 20.5 12C21 12 21 11.6 21 11.2V9.5" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.5 12V19C4.5 19.55 4.95 20 5.5 20H18.5C19.05 20 19.5 19.55 19.5 19V12" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.5 20V15C9.5 14.45 9.95 14 10.5 14H13.5C14.05 14 14.5 14.45 14.5 15V20" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="branch-name">{{ $company->name }}</div>
                    <span class="branch-year-badge">{{ $fy->financial_year ?? $fy->year }}</span>

                    <span class="branch-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            @else
                <div class="branch-card disabled" title="لا توجد سنة مالية مسجلة لهذا الفرع">
                    <div class="branch-avatar">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9.5L4.2 4H19.8L21 9.5" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.5 12V19C4.5 19.55 4.95 20 5.5 20H18.5C19.05 20 19.5 19.55 19.5 19V12" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="branch-name">{{ $company->name }}</div>
                    <span class="branch-year-badge">لا توجد سنة مالية</span>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection