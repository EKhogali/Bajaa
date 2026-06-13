@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">كشف الحساب المتقدم للموردين</h1>
            </div>
        </div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">
        
        <div class="card card-primary card-outline shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title text-right w-100 font-weight-bold" style="float: right;">
                    <i class="fas fa-filter ml-2 text-primary"></i> خيارات البحث والفلترة التفصيلية
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.vendor_report') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold">المورد المالي</label>
                            <select class="form-control" name="vendor_id">
                                <option value="">-- كل الموردين المسجلين --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold">من تاريخ</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold">إلى تاريخ</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold">تصنيف الموردين (وسوم المورد)</label>
                            <select class="form-control" name="vendor_tag_id">
                                <option value="">-- كل التصنيفات --</option>
                                @foreach($vendorTags as $vTag)
                                    <option value="{{ $vTag->id }}" {{ request('vendor_tag_id') == $vTag->id ? 'selected' : '' }}>
                                        {{ $vTag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold">نوع المعاملة المالية (وسوم الحركة)</label>
                            <select class="form-control" name="transaction_tag_id">
                                <option value="">-- كل الحركات المادية --</option>
                                @foreach($transactionTags as $tTag)
                                    <option value="{{ $tTag->id }}" {{ request('transaction_tag_id') == $tTag->id ? 'selected' : '' }}>
                                        {{ $tTag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group mb-3 d-flex align-items-end" style="gap: 10px;">
                            <button type="submit" class="btn btn-primary font-weight-bold flex-grow-1 shadow-sm">
                                <i class="fas fa-calculator ml-1"></i> استخراج التقرير
                            </button>
                            <a href="{{ route('reports.vendor_report') }}" class="btn btn-default border">تصفية الكل</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box bg-light shadow-sm border text-center d-block py-2">
                    <span class="info-box-text text-muted font-weight-bold">الرصيد الافتتاحي المنقول</span>
                    <h4 class="info-box-number mt-1 font-weight-bold {{ $previousBalance < 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($previousBalance, 2) }}
                    </h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-light shadow-sm border text-center d-block py-2">
                    <span class="info-box-text text-muted font-weight-bold">إجمالي مدين (+) له</span>
                    <h4 class="info-box-number mt-1 text-success font-weight-bold">{{ number_format($totalDebit, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-light shadow-sm border text-center d-block py-2">
                    <span class="info-box-text text-muted font-weight-bold">إجمالي دائن (-) عليه</span>
                    <h4 class="info-box-number mt-1 text-danger font-weight-bold">{{ number_format($totalCredit, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-dark shadow-sm text-center d-block py-2">
                    <span class="info-box-text font-weight-bold text-white">صافي رصيد الحساب الختامي</span>
                    <h4 class="info-box-number mt-1 text-warning font-weight-bold">{{ number_format($finalBalance, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-dark m-0" style="flex: 1;">
                    <i class="fas fa-file-alt ml-2 text-secondary"></i> السجلات والقيود الناتجة عن البحث المتقدم
                </h3>
                <div class="card-tools">
                    <button onclick="window.print();" class="btn btn-sm btn-default border font-weight-bold">
                        <i class="fas fa-print ml-1"></i> طباعة النسخة الورقية
                    </button>
                </div>
            </div>
            
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped text-center mb-0">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 110px;">التاريخ</th>
                            <th>المورد</th>
                            <th>البيان والتفاصيل</th>
                            <th>الوسوم</th>
                            <th style="width: 120px;">مدين (+)</th>
                            <th style="width: 120px;">دائن (-)</th>
                            <th style="width: 140px;">الرصيد التراكمي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(request('from_date'))
                            <tr class="bg-light text-muted font-weight-bold">
                                <td>-</td>
                                <td>{{ request('from_date') }}</td>
                                <td>{{ request('vendor_id') ? $vendors->firstWhere('id', request('vendor_id'))->name : 'كل الموردين' }}</td>
                                <td class="text-right px-3">رصيد حساب مرحل سابق لتاريخ النطاق الحالي...</td>
                                <td><span class="badge badge-secondary">افتتاحي</span></td>
                                <td>---</td>
                                <td>---</td>
                                <td class="{{ $previousBalance < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($previousBalance, 2) }}</td>
                            </tr>
                        @endif

                        @php $currentTrackBalance = $previousBalance; @endphp
                        @forelse($transactions as $transaction)
                            @php 
                                $currentTrackBalance += $transaction->debit - $transaction->credit; 
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                                <td class="font-weight-bold text-primary">{{ $transaction->vendor->name ?? '---' }}</td>
                                <td class="text-right px-3 text-muted small">{{ $transaction->description ?? '---' }}</td>
                                <td>
                                    @foreach($transaction->tags as $tag)
                                        <span class="badge badge-warning px-1 text-dark">{{ $tag->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-success font-weight-bold">{{ $transaction->debit > 0 ? number_format($transaction->debit, 2) : '---' }}</td>
                                <td class="text-danger font-weight-bold">{{ $transaction->credit > 0 ? number_format($transaction->credit, 2) : '---' }}</td>
                                <td class="font-weight-bold {{ $currentTrackBalance < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($currentTrackBalance, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-exclamation-circle d-block mb-2 text-warning fa-lg"></i>
                                    لا توجد سجلات مالية متوافقة مع معايير الفلترة والبحث الحالية.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection