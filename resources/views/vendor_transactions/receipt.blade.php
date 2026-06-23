<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>سند {{ $transaction->debit > 0 ? 'قبض' : 'صرف' }} #{{ $transaction->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 15mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            font-size: 14px;
            color: #1a1a1a;
            background: #fff;
            direction: rtl;
            padding-top: 52px;
            /* toolbar offset on screen */
        }

        /* ── Screen toolbar ── */
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #2c3e50;
            color: #fff;
            padding: 9px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 999;
            font-size: 13px;
        }

        .btn-print {
            background: #27ae60;
            color: #fff;
            border: none;
            padding: 7px 22px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #219a52;
        }

        .btn-back {
            background: #c0392b;
            color: #fff;
            border: none;
            padding: 7px 18px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-right: auto;
        }

        .btn-back:hover {
            background: #a93226;
        }

        /* ── Receipt wrapper ── */
        .receipt {
            width: 100%;
            max-width: 180mm;
            margin: 0 auto;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            overflow: hidden;
        }

        /* ── Type banner ── */
        .receipt-banner {
            padding: 14px 20px;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .receipt-banner.debit {
            background: #1a7a4a;
        }

        .receipt-banner.credit {
            background: #922b21;
        }

        .receipt-banner .type-label {
            font-size: 22px;
            font-weight: 900;
            color: #fff;
            letter-spacing: 1px;
            display: block;
        }

        .receipt-banner .type-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, .75);
            margin-top: 3px;
            display: block;
        }

        /* ── Header info row ── */
        .receipt-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 14px 20px 10px;
            border-bottom: 1px solid #e0e3e8;
            background: #f8f9fa;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .company-name {
            font-size: 17px;
            font-weight: 800;
            color: #2c3e50;
        }

        .company-sub {
            font-size: 12px;
            color: #777;
            margin-top: 3px;
        }

        .receipt-meta {
            text-align: left;
            font-size: 12px;
            color: #555;
            line-height: 1.9;
        }

        .receipt-meta strong {
            color: #2c3e50;
        }

        /* ── Body fields ── */
        .receipt-body {
            padding: 18px 20px;
        }

        .field-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 14px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 10px;
            gap: 10px;
        }

        .field-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .field-label {
            font-size: 13px;
            font-weight: 700;
            color: #555;
            white-space: nowrap;
            min-width: 110px;
        }

        .field-value {
            font-size: 14px;
            color: #1a1a1a;
            flex: 1;
            font-weight: 500;
        }

        /* ── Amount highlight ── */
        .amount-box {
            border: 2px solid;
            border-radius: 6px;
            padding: 12px 18px;
            margin: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .amount-box.debit {
            border-color: #1a7a4a;
            background: #f0fff6;
        }

        .amount-box.credit {
            border-color: #922b21;
            background: #fff5f5;
        }

        .amount-label {
            font-size: 13px;
            font-weight: 700;
        }

        .amount-box.debit .amount-label {
            color: #1a7a4a;
        }

        .amount-box.credit .amount-label {
            color: #922b21;
        }

        .amount-value {
            font-size: 26px;
            font-weight: 900;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.5px;
        }

        .amount-box.debit .amount-value {
            color: #1a7a4a;
        }

        .amount-box.credit .amount-value {
            color: #922b21;
        }

        /* ── Tags ── */
        .tags-row {
            padding: 0 20px 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .tag-pill {
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .tag-pill:nth-child(8n+1) {
            background: #3498db;
        }

        .tag-pill:nth-child(8n+2) {
            background: #2ecc71;
        }

        .tag-pill:nth-child(8n+3) {
            background: #e67e22;
        }

        .tag-pill:nth-child(8n+4) {
            background: #9b59b6;
        }

        .tag-pill:nth-child(8n+5) {
            background: #e74c3c;
        }

        .tag-pill:nth-child(8n+6) {
            background: #1abc9c;
        }

        .tag-pill:nth-child(8n+7) {
            background: #e91e8c;
        }

        .tag-pill:nth-child(8n+8) {
            background: #f39c12;
        }

        /* ── Signatures ── */
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            padding: 18px 20px 16px;
            border-top: 1px solid #e0e3e8;
            margin-top: 4px;
        }

        .sig-block {
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #555;
            margin: 28px 10px 6px;
        }

        .sig-label {
            font-size: 11px;
            color: #777;
            font-weight: 600;
        }

        /* ── Footer ── */
        .receipt-footer {
            background: #f8f9fa;
            padding: 8px 20px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #e0e3e8;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @media print {
            body {
                background: #fff;
                padding-top: 0 !important;
            }

            .print-toolbar {
                display: none !important;
            }

            .receipt {
                border: 2px solid #2c3e50;
            }
        }
    </style>
</head>

<body>

    {{-- Screen toolbar --}}
    <div class="print-toolbar">
        <button class="btn-print" onclick="window.print()">🖨 طباعة السند</button>
        <span>
            سند {{ $transaction->debit > 0 ? 'قبض' : 'صرف' }}
            رقم #{{ $transaction->id }}
        </span>
        <button class="btn-back" onclick="window.history.back()">✕ رجوع</button>
    </div>

    @php
        $isDebit = $transaction->debit > 0;
        $type = $isDebit ? 'debit' : 'credit';
        $amount = $isDebit ? $transaction->debit : $transaction->credit;
    @endphp

    <div class="receipt">

        {{-- ── Banner ── --}}
        <div class="receipt-banner {{ $type }}">
            <span class="type-label">
                {{ $isDebit ? 'سـنـد قـبـض' : 'سـنـد صـرف' }}
            </span>
            <span class="type-sub">
                {{ $isDebit
    ? 'إثبات استلام مبلغ مالي من المورد'
    : 'إثبات صرف مبلغ مالي للمورد' }}
            </span>
        </div>

        {{-- ── Header ── --}}
        <div class="receipt-head">
            <div>
                <div class="company-name">{{ session('company_name', 'الشركة') }}</div>
                <div class="company-sub">سجل الموردين والمعاملات المالية</div>
            </div>
            <div class="receipt-meta">
                <div><strong>رقم السند:</strong> #{{ $transaction->id }}</div>
                <div><strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</div>
                <div><strong>وقت الطباعة:</strong> {{ now()->format('H:i') }}</div>
            </div>
        </div>

        {{-- ── Amount ── --}}
        <div class="amount-box {{ $type }}">
            <span class="amount-label">
                {{ $isDebit ? 'المبلغ المستلم' : 'المبلغ المصروف' }}
            </span>
            <span class="amount-value">{{ number_format($amount, 2) }}</span>
        </div>

        {{-- ── Fields ── --}}
        <div class="receipt-body">

            <div class="field-row">
                <span class="field-label">اسم المورد</span>
                <span class="field-value">{{ $transaction->vendor->name ?? '—' }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">نوع العملية</span>
                <span class="field-value" style="font-weight:700; color: {{ $isDebit ? '#1a7a4a' : '#922b21' }};">
                    {{ $isDebit ? 'مدين (+) — مبلغ مستحق للمورد / فاتورة شراء' : 'دائن (−) — سداد نقدي أو دفعة للمورد' }}
                </span>
            </div>

            <div class="field-row">
                <span class="field-label">البيان / الشرح</span>
                <span class="field-value" style="color:#555;">
                    {{ $transaction->description ? strip_tags($transaction->description) : '—' }}
                </span>
            </div>

            <div class="field-row">
                <span class="field-label">تاريخ القيد</span>
                <span class="field-value">
                    {{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}
                </span>
            </div>

        </div>

        {{-- ── Tags ── --}}
        @if($transaction->tags->count() > 0)
            <div class="tags-row">
                <span style="font-size:12px; font-weight:700; color:#555; margin-left:6px;">الوسوم:</span>
                @foreach($transaction->tags as $tag)
                    <span class="tag-pill">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        {{-- ── Signatures ── --}}
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">المستلم / المحاسب</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">المراجع</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">المدير المالي</div>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="receipt-footer">
            تم إصدار هذا السند بتاريخ {{ now()->format('Y-m-d') }} الساعة {{ now()->format('H:i') }}
            &mdash; {{ session('company_name', '') }}
        </div>

    </div>

</body>

</html>