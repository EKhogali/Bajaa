@extends('layout.master')

@section('content')

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ── Select2 pill styling ── */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            min-height: 38px;
            padding: 3px 6px;
            background-color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #ffc107;
            color: #212529;
            border: none;
            border-radius: 20px;
            padding: 2px 12px;
            font-size: 12px;
            font-weight: bold;
            margin: 2px 3px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #555;
            margin-left: 6px;
            font-weight: bold;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #c00;
        }

        .select2-container {
            width: 100% !important;
        }

        /* ── Summary cards ── */
        .summary-card {
            border-radius: 8px;
            padding: 14px 10px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
        }

        .summary-card .s-label {
            font-size: 12px;
            font-weight: 700;
            color: #6c757d;
            display: block;
            margin-bottom: 4px;
        }

        .summary-card .s-value {
            font-size: 20px;
            font-weight: 800;
            display: block;
        }

        /* ── Print ── */
        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>

    {{-- ═══════════════════════════════════════════
    PAGE HEADER
    ════════════════════════════════════════════ --}}
    <div class="content-header text-right no-print">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-file-invoice-dollar ml-2 text-primary"></i>
                        كشف الحساب المتقدم للموردين
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content text-right" dir="rtl">
        <div class="container-fluid">

            {{-- ═══════════════════════════════════════════
            FILTER CARD
            ════════════════════════════════════════════ --}}
            <div class="card card-primary card-outline shadow-sm mb-4 no-print">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold w-100 text-right" style="float:right;">
                        <i class="fas fa-sliders-h ml-2 text-primary"></i> خيارات البحث والفلترة
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.vendor_report') }}" method="GET" id="filterForm">
                        <div class="row align-items-end">

                            {{-- Vendor --}}
                            <div class="col-md-4 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">المورد المالي</label>
                                <select class="form-control" name="vendor_id">
                                    <option value="">-- كل الموردين --</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- From Date — default: first day of current month --}}
                            <div class="col-md-2 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">من تاريخ</label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ request('from_date', now()->startOfMonth()->toDateString()) }}">
                            </div>

                            {{-- To Date — default: today --}}
                            <div class="col-md-2 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">إلى تاريخ</label>
                                <input type="date" class="form-control" name="to_date"
                                    value="{{ request('to_date', now()->toDateString()) }}">
                            </div>

                            {{-- Vendor Tags (multi) --}}
                            <div class="col-md-4 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">وسوم المورد</label>
                                <select class="select2-tags" name="vendor_tag_ids[]" multiple>
                                    @foreach($vendorTags as $vTag)
                                        <option value="{{ $vTag->id }}" {{ in_array($vTag->id, request('vendor_tag_ids', [])) ? 'selected' : '' }}>
                                            {{ $vTag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Transaction Tags (multi) --}}
                            <div class="col-md-4 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">وسوم الحركة</label>
                                <select class="select2-tags" name="transaction_tag_ids[]" multiple>
                                    @foreach($transactionTags as $tTag)
                                        <option value="{{ $tTag->id }}" {{ in_array($tTag->id, request('transaction_tag_ids', [])) ? 'selected' : '' }}>
                                            {{ $tTag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Actions --}}
                            <div class="col-md-4 form-group mb-3 d-flex" style="gap:8px;">
                                <button type="submit" class="btn btn-primary font-weight-bold flex-grow-1">
                                    <i class="fas fa-search ml-1"></i> استخراج التقرير
                                </button>
                                <a href="{{ route('reports.vendor_report') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo ml-1"></i> إعادة تعيين
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
            SUMMARY CARDS
            ════════════════════════════════════════════ --}}
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-2">
                    <div class="summary-card bg-white border">
                        <span class="s-label">الرصيد الافتتاحي المنقول</span>
                        <span class="s-value {{ $previousBalance < 0 ? 'text-danger' : 'text-secondary' }}">
                            {{ number_format($previousBalance, 2) }}
                        </span>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="summary-card bg-white border">
                        <span class="s-label">إجمالي المدين (+)</span>
                        <span class="s-value text-success">{{ number_format($totalDebit, 2) }}</span>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="summary-card bg-white border">
                        <span class="s-label">إجمالي الدائن (−)</span>
                        <span class="s-value text-danger">{{ number_format($totalCredit, 2) }}</span>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="summary-card bg-dark">
                        <span class="s-label text-white-50">صافي الرصيد الختامي</span>
                        <span class="s-value text-warning">{{ number_format($finalBalance, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
            RESULTS TABLE
            ════════════════════════════════════════════ --}}
            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark m-0">
                        <i class="fas fa-table ml-2 text-secondary"></i>
                        سجلات الحركات المالية
                        <small class="text-muted font-weight-normal mr-2">
                            ({{ $transactions->count() }} حركة)
                        </small>
                    </h3>
                    <div class="no-print">
                        <a href="{{ route('reports.vendor_report_print') . '?' . http_build_query(request()->all()) }}"
                            target="_blank" class="btn btn-sm btn-outline-secondary font-weight-bold no-print">
                            <i class="fas fa-print ml-1"></i> طباعة
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm text-center mb-0" style="font-size:13px;">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th style="width:45px;">#</th>
                                    <th style="width:100px;">التاريخ</th>
                                    <th>المورد</th>
                                    <th>البيان</th>
                                    <th style="width:130px;">الوسوم</th>
                                    <th style="width:110px;">مدين (+)</th>
                                    <th style="width:110px;">دائن (−)</th>
                                    <th style="width:130px;">الرصيد التراكمي</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- Opening balance row --}}
                                @if(request('from_date'))
                                                        <tr class="table-light text-muted" style="font-size:12px;">
                                                            <td>—</td>
                                                            <td>{{ request('from_date') }}</td>
                                                            <td class="font-weight-bold">
                                                                {{ request('vendor_id')
                                    ? ($vendors->firstWhere('id', request('vendor_id'))->name ?? '—')
                                    : 'كل الموردين' }}
                                                            </td>
                                                            <td class="text-right px-2">رصيد مرحّل سابق</td>
                                                            <td>
                                                                <span class="badge badge-secondary" style="border-radius:20px;">افتتاحي</span>
                                                            </td>
                                                            <td class="text-muted">—</td>
                                                            <td class="text-muted">—</td>
                                                            <td
                                                                class="font-weight-bold {{ $previousBalance < 0 ? 'text-danger' : 'text-success' }}">
                                                                {{ number_format($previousBalance, 2) }}
                                                            </td>
                                                        </tr>
                                @endif

                                {{-- Transaction rows --}}
                                @php $runningBalance = $previousBalance; @endphp

                                @forelse($transactions as $t)
                                    @php $runningBalance += $t->debit - $t->credit; @endphp
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($t->date)->format('Y-m-d') }}</td>
                                        <td class="font-weight-bold text-primary">{{ $t->vendor->name ?? '—' }}</td>
                                        <td class="text-right px-2 text-muted small">{{ $t->description ?? '—' }}</td>
                                        <td>
                                            @forelse($t->tags as $tag)
                                                <span class="badge badge-warning text-dark px-2"
                                                    style="border-radius:20px; font-size:11px; margin:1px;">
                                                    {{ $tag->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted">—</span>
                                            @endforelse
                                        </td>
                                        <td class="font-weight-bold text-success">
                                            {{ $t->debit > 0 ? number_format($t->debit, 2) : '—' }}
                                        </td>
                                        <td class="font-weight-bold text-danger">
                                            {{ $t->credit > 0 ? number_format($t->credit, 2) : '—' }}
                                        </td>
                                        <td class="font-weight-bold {{ $runningBalance < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($runningBalance, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-2x d-block mb-2 text-secondary"></i>
                                            لا توجد حركات مالية مطابقة لمعايير البحث الحالية.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                            {{-- Footer totals (only when there are rows) --}}
                            @if($transactions->count() > 0)
                                <tfoot class="bg-light font-weight-bold" style="font-size:13px;">
                                    <tr>
                                        <td colspan="5" class="text-right px-3">المجموع الكلي للفترة</td>
                                        <td class="text-success">{{ number_format($totalDebit, 2) }}</td>
                                        <td class="text-danger">{{ number_format($totalCredit, 2) }}</td>
                                        <td class="{{ $finalBalance < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($finalBalance, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2-tags').select2({
                dir: 'rtl',
                placeholder: '-- اختر وسوماً --',
                allowClear: true,
            });
        });
    </script>

@endsection