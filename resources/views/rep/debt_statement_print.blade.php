<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف المديونية</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 portrait; margin: 12mm 10mm; }
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 13px; color: #1a1a1a; direction: rtl; }
        .report-header { display: flex; justify-content: space-between; border-bottom: 3px solid #2c3e50; padding-bottom: 10px; margin-bottom: 14px; }
        .company-name { font-size: 22px; font-weight: 800; color: #2c3e50; }
        .report-title { font-size: 14px; font-weight: 600; color: #666; margin-top: 4px; }
        .meta-block { text-align: left; font-size: 12px; color: #555; line-height: 1.9; }
        .scope-bar {
            background: #f4f6f8; border: 1px solid #dde1e6; border-radius: 5px;
            padding: 8px 12px; margin-bottom: 12px; font-size: 13px; font-weight: 700; color: #2c3e50;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        .summary-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 14px; }
        .summary-card { border: 1px solid #dde1e6; border-radius: 6px; padding: 8px 10px; text-align: center; }
        .summary-card .s-label { font-size: 11px; color: #777; font-weight: 600; display: block; margin-bottom: 4px; }
        .summary-card .s-value { font-size: 18px; font-weight: 800; display: block; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 14px; }
        thead tr { background: #2c3e50; color: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        thead th { padding: 9px 10px; font-weight: 700; text-align: center; border: 1px solid #1a252f; }
        tbody tr:nth-child(even) { background: #f9fafb; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        tbody td { padding: 7px 10px; text-align: center; border: 1px solid #e0e3e8; }
        .text-success { color: #27ae60; }
        .text-danger { color: #c0392b; }
        .print-toolbar { position: fixed; top: 0; left: 0; right: 0; background: #2c3e50; color: #fff; padding: 9px 20px; display: flex; align-items: center; gap: 12px; z-index: 999; font-size: 13px; }
        .print-toolbar .btn-print { background: #27ae60; color: #fff; border: none; padding: 7px 22px; border-radius: 4px; font-weight: 700; cursor: pointer; }
        .print-toolbar .btn-back { background: #c0392b; color: #fff; border: none; padding: 7px 18px; border-radius: 4px; font-weight: 700; cursor: pointer; margin-right: auto; }
        @media print {
            .no-print { display: none !important; }
            body { padding-top: 0 !important; }
        }
        body { padding-top: 48px; }
    </style>
</head>
<body>

<div class="print-toolbar no-print">
    <button class="btn-print" onclick="window.print()">🖨 طباعة التقرير</button>
    <span>معاينة التقرير قبل الطباعة</span>
    <button class="btn-back" onclick="window.history.back()">✕ رجوع</button>
</div>

<div class="page">
    <div class="report-header">
        <div>
            <div class="company-name">{{ session('company_name', 'الشركة') }}</div>
            <div class="report-title">كشف المديونية</div>
        </div>
        <div class="meta-block">
            <div><strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d') }}</div>
            <div><strong>وقت الطباعة:</strong> {{ now()->format('H:i') }}</div>
            <div><strong>عدد الموردين:</strong> {{ $vendors->count() }}</div>
        </div>
    </div>

    {{-- Scope phrase: comprehensive / specific company / specific classification / specific vendor --}}
    <div class="scope-bar">
        النطاق: {{ $scopeLabel }} &nbsp;|&nbsp; الشركة: {{ $companyLabel }} &nbsp;|&nbsp; {{ $periodLabel }}
    </div>

    <div class="summary-row">
        <div class="summary-card">
            <span class="s-label">إجمالي المدين</span>
            <span class="s-value text-success">{{ number_format($totalDebit, 2) }}</span>
        </div>
        <div class="summary-card">
            <span class="s-label">إجمالي الدائن</span>
            <span class="s-value text-danger">{{ number_format($totalCredit, 2) }}</span>
        </div>
        <div class="summary-card">
            <span class="s-label">صافي الرصيد</span>
            <span class="s-value">{{ number_format($netBalance, 2) }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>اسم المورد</th>
                <th>الشركة</th>
                <th>التصنيف</th>
                <th style="width:130px;">{{ $periodLabel === 'كل الفترات' ? 'الرصيد الحالي' : 'صافي حركة الفترة' }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendors as $vendor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight:700; color:#2980b9;">{{ $vendor->name }}</td>
                    <td>{{ $vendor->company->name ?? '—' }}</td>
                    <td>{{ $vendor->group->name ?? '—' }}</td>
                    <td class="{{ $vendor->display_balance < 0 ? 'text-danger' : 'text-success' }}" style="font-weight:700;">
                        {{ number_format($vendor->display_balance, 2) }}
                        {{ $vendor->display_balance < 0 ? '(دائن)' : ($vendor->display_balance > 0 ? '(مدين)' : '') }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; padding:24px; color:#999;">لا توجد بيانات مطابقة.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>