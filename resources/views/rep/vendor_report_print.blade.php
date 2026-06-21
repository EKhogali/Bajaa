<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الموردين</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            font-size: 14px;
            color: #1a1a1a;
            background: #fff;
            direction: rtl;
        }

        .page {
            width: 100%;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm 8mm;
        }

        /* ── Header ── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .company-block .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #2c3e50;
        }
        .company-block .report-title {
            font-size: 15px;
            font-weight: 600;
            color: #666;
            margin-top: 4px;
        }
        .meta-block {
            text-align: left;
            font-size: 13px;
            color: #555;
            line-height: 1.9;
        }
        .meta-block strong { color: #2c3e50; }

        /* ── Filter bar ── */
        .filter-bar {
            background: #f4f6f8;
            border: 1px solid #dde1e6;
            border-radius: 5px;
            padding: 9px 14px;
            margin-bottom: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px 24px;
            font-size: 13px;
            color: #444;
        }
        .filter-bar .filter-label { font-weight: 700; color: #2c3e50; margin-left: 4px; }

        /* ── Summary cards ── */
        .summary-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }
        .summary-card {
            border: 1px solid #dde1e6;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }
        .summary-card .s-label {
            font-size: 12px;
            color: #777;
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }
        .summary-card .s-value {
            font-size: 20px;
            font-weight: 800;
            display: block;
        }
        .summary-card.dark { background: #2c3e50; border-color: #2c3e50; }
        .summary-card.dark .s-label { color: #99aabb; }
        .summary-card.dark .s-value { color: #f1c40f; }
        .text-success { color: #27ae60; }
        .text-danger  { color: #c0392b; }
        .text-muted   { color: #999; }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 16px;
            table-layout: fixed;
        }
        thead tr {
            background: #2c3e50;
            color: #fff;
        }
        thead th {
            padding: 10px 8px;
            font-weight: 700;
            text-align: center;
            border: 1px solid #1a252f;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
        }
        tbody tr { border-bottom: 1px solid #e8eaed; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody tr.opening-row { background: #eef2f7; font-style: italic; color: #666; }
        tbody tr.total-row {
            background: #edf2f7;
            font-weight: 800;
            border-top: 2px solid #2c3e50;
        }
        tbody td {
            padding: 8px;
            text-align: center;
            border: 1px solid #e0e3e8;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        td.desc-cell {
            text-align: right;
            padding-right: 10px;
            font-size: 13px;
            line-height: 1.5;
            color: #444;
        }
        td.amount-cell {
            font-weight: 700;
            font-size: 13px;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }
        td.balance-cell {
            font-weight: 800;
            font-size: 13px;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        /* Column widths */
        .col-num     { width: 40px; }
        .col-date    { width: 95px; }
        .col-vendor  { width: 130px; }
        .col-desc    { width: auto; }      /* takes remaining space */
        .col-debit   { width: 115px; }
        .col-credit  { width: 115px; }
        .col-balance { width: 125px; }

        /* ── Footer ── */
        .report-footer {
            border-top: 2px solid #2c3e50;
            padding-top: 9px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #888;
            margin-top: 12px;
        }

        /* ── Print ── */
        @media print {
            body { background: #fff; padding-top: 0; }
            .page { padding: 8mm 6mm; width: 100%; }
            .no-print { display: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }

        /* ── Screen toolbar ── */
        .print-toolbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #2c3e50;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 999;
            font-size: 13px;
        }
        .print-toolbar .btn-print {
            background: #27ae60;
            color: #fff;
            border: none;
            padding: 7px 22px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        .print-toolbar .btn-print:hover { background: #219a52; }
        .print-toolbar .btn-back {
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
        .print-toolbar .btn-back:hover { background: #a93226; }

        body { padding-top: 50px; }
    </style>
</head>
<body>

{{-- Screen toolbar --}}
<div class="print-toolbar no-print">
    <button class="btn-print" onclick="window.print()">🖨 طباعة التقرير</button>
    <span>معاينة التقرير قبل الطباعة</span>
    <button class="btn-back" onclick="window.history.back()">✕ رجوع</button>
</div>

<div class="page">

    {{-- ══ HEADER ══ --}}
    <div class="report-header">
        <div class="company-block">
            <div class="company-name">{{ session('company_name', 'الشركة') }}</div>
            <div class="report-title">كشف الحساب المتقدم للموردين</div>
        </div>
        <div class="meta-block">
            <div><strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d') }}</div>
            <div><strong>وقت الطباعة:</strong> {{ now()->format('H:i') }}</div>
            <div><strong>عدد الحركات:</strong> {{ $transactions->count() }}</div>
        </div>
    </div>

    {{-- ══ FILTER BAR ══ --}}
    <div class="filter-bar">
        <div>
            <span class="filter-label">المورد:</span>
            <span>{{ $filterVendor ?? 'كل الموردين' }}</span>
        </div>
        <div>
            <span class="filter-label">من:</span>
            <span>{{ $filterFromDate ?? '—' }}</span>
        </div>
        <div>
            <span class="filter-label">إلى:</span>
            <span>{{ $filterToDate ?? '—' }}</span>
        </div>
        @if(!empty($filterVendorTags))
        <div>
            <span class="filter-label">وسوم المورد:</span>
            <span>{{ $filterVendorTags }}</span>
        </div>
        @endif
        @if(!empty($filterTransactionTags))
        <div>
            <span class="filter-label">وسوم الحركة:</span>
            <span>{{ $filterTransactionTags }}</span>
        </div>
        @endif
    </div>

    {{-- ══ SUMMARY CARDS ══ --}}
    <div class="summary-row">
        <div class="summary-card">
            <span class="s-label">الرصيد الافتتاحي</span>
            <span class="s-value {{ $previousBalance < 0 ? 'text-danger' : 'text-muted' }}">
                {{ number_format($previousBalance, 2) }}
            </span>
        </div>
        <div class="summary-card">
            <span class="s-label">إجمالي المدين (+)</span>
            <span class="s-value text-success">{{ number_format($totalDebit, 2) }}</span>
        </div>
        <div class="summary-card">
            <span class="s-label">إجمالي الدائن (−)</span>
            <span class="s-value text-danger">{{ number_format($totalCredit, 2) }}</span>
        </div>
        <div class="summary-card dark">
            <span class="s-label">صافي الرصيد الختامي</span>
            <span class="s-value">{{ number_format($finalBalance, 2) }}</span>
        </div>
    </div>

    {{-- ══ TABLE ══ --}}
    <table>
        <thead>
            <tr>
                <th class="col-num">#</th>
                <th class="col-date">التاريخ</th>
                <th class="col-vendor">المورد</th>
                <th class="col-desc">البيان والتفاصيل</th>
                <th class="col-debit">مدين (+)</th>
                <th class="col-credit">دائن (−)</th>
                <th class="col-balance">الرصيد التراكمي</th>
            </tr>
        </thead>
        <tbody>

            {{-- Opening balance row --}}
            @if($filterFromDate)
            <tr class="opening-row">
                <td>—</td>
                <td>{{ $filterFromDate }}</td>
                <td>{{ $filterVendor ?? 'كل الموردين' }}</td>
                <td class="desc-cell">رصيد مرحّل سابق لتاريخ بداية الفترة</td>
                <td class="amount-cell text-muted">—</td>
                <td class="amount-cell text-muted">—</td>
                <td class="balance-cell {{ $previousBalance < 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($previousBalance, 2) }}
                </td>
            </tr>
            @endif

            {{-- Rows --}}
            @php $running = $previousBalance; @endphp
            @forelse($transactions as $t)
                @php $running += $t->debit - $t->credit; @endphp
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->date)->format('Y-m-d') }}</td>
                    <td style="font-weight:700; color:#2980b9;">{{ $t->vendor->name ?? '—' }}</td>
                    <td class="desc-cell">{{ $t->description ?? '—' }}</td>
                    <td class="amount-cell text-success">
                        {{ $t->debit > 0 ? number_format($t->debit, 2) : '—' }}
                    </td>
                    <td class="amount-cell text-danger">
                        {{ $t->credit > 0 ? number_format($t->credit, 2) : '—' }}
                    </td>
                    <td class="balance-cell {{ $running < 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($running, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:24px; color:#999;">
                        لا توجد حركات مالية مطابقة لمعايير البحث.
                    </td>
                </tr>
            @endforelse

            {{-- Totals row --}}
            @if($transactions->count() > 0)
            <tr class="total-row">
                <td colspan="4" style="text-align:right; padding-right:14px; font-size:14px;">
                    المجموع الكلي للفترة
                </td>
                <td class="amount-cell text-success">{{ number_format($totalDebit, 2) }}</td>
                <td class="amount-cell text-danger">{{ number_format($totalCredit, 2) }}</td>
                <td class="balance-cell {{ $finalBalance < 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($finalBalance, 2) }}
                </td>
            </tr>
            @endif

        </tbody>
    </table>

    {{-- ══ FOOTER ══ --}}
    <div class="report-footer">
        <span>{{ session('company_name', '') }} &mdash; كشف حساب الموردين</span>
        <span>تم الإنشاء بتاريخ {{ now()->format('Y-m-d H:i') }}</span>
    </div>

</div>
</body>
</html>