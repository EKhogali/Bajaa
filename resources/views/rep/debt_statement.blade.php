@extends('layout.master')

@section('content')
    <div class="content-header text-right">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0 text-dark"><i class="fas fa-file-invoice-dollar ml-2 text-primary"></i> كشف المديونية
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content text-right" dir="rtl">
        <div class="container-fluid">

            {{-- FILTER CARD --}}
            <div class="card card-primary card-outline shadow-sm mb-4 no-print">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-sliders-h ml-2 text-primary"></i> خيارات البحث
                        والفلترة</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.debt_statement') }}" method="GET">
                        <div class="row align-items-end">

                            <div class="col-md-3 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">المورد</label>
                                <select class="form-control" name="vendor_id">
                                    <option value="">-- كل الموردين --</option>
                                    @foreach($allVendors as $v)
                                        <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">التصنيف</label>
                                <select class="form-control" name="vendor_group_id">
                                    <option value="">-- كل التصنيفات --</option>
                                    @foreach($groups as $g)
                                        <option value="{{ $g->id }}" {{ request('vendor_group_id') == $g->id ? 'selected' : '' }}>
                                            {{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label class="font-weight-bold text-secondary small mb-1">الشركة</label>
                                @if($isAdminOrSupervisor)
                                    <select class="form-control" name="company_filter">
                                        <option value="current" {{ request('company_filter', 'current') == 'current' ? 'selected' : '' }}>الشركة الحالية</option>
                                        <option value="all" {{ request('company_filter') == 'all' ? 'selected' : '' }}>كل الشركات
                                        </option>
                                        @foreach($companies as $c)
                                            <option value="{{ $c->id }}" {{ request('company_filter') == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control"
                                        value="{{ session('company_name', 'الشركة الحالية') }}" disabled>
                                    <input type="hidden" name="company_filter" value="current">
                                @endif
                            </div>

                            <div class="col-md-3 form-group mb-3 d-flex" style="gap:8px;">
                                <button type="submit" class="btn btn-primary font-weight-bold flex-grow-1">
                                    <i class="fas fa-search ml-1"></i> استخراج التقرير
                                </button>
                                <a href="{{ route('reports.debt_statement') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo ml-1"></i> إعادة تعيين
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="row mb-4">
                <div class="col-6 col-md-4 mb-2">
                    <div class="p-3 bg-white border rounded text-center shadow-sm">
                        <span class="d-block small text-muted font-weight-bold">إجمالي المدين</span>
                        <span class="d-block h5 text-success font-weight-bold">{{ number_format($totalDebit, 2) }}</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <div class="p-3 bg-white border rounded text-center shadow-sm">
                        <span class="d-block small text-muted font-weight-bold">إجمالي الدائن</span>
                        <span class="d-block h5 text-danger font-weight-bold">{{ number_format($totalCredit, 2) }}</span>
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <div class="p-3 bg-dark rounded text-center shadow-sm">
                        <span class="d-block small text-white-50 font-weight-bold">صافي الرصيد</span>
                        <span class="d-block h5 text-warning font-weight-bold">{{ number_format($netBalance, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- RESULTS TABLE --}}
            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold m-0">
                        <i class="fas fa-table ml-2 text-secondary"></i> قائمة الموردين
                        <small class="text-muted font-weight-normal mr-2">({{ $vendors->count() }} مورد)</small>
                    </h3>
                    <a href="{{ route('reports.debt_statement_print') . '?' . http_build_query(request()->all()) }}"
                        target="_blank" class="btn btn-sm btn-outline-secondary font-weight-bold">
                        <i class="fas fa-print ml-1"></i> طباعة
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover table-sm text-center mb-0">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th style="width:45px;">#</th>
                                <th>اسم المورد</th>
                                <th>الشركة</th>
                                <th>التصنيف</th>
                                <th style="width:150px;">الرصيد الحالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $vendor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="font-weight-bold text-primary">{{ $vendor->name }}</td>
                                    <td>{{ $vendor->company->name ?? '—' }}</td>
                                    <td>{{ $vendor->group->name ?? '—' }}</td>
                                    <td class="font-weight-bold {{ $vendor->balance < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($vendor->balance, 2) }}
                                        {{ $vendor->balance < 0 ? '(دائن)' : ($vendor->balance > 0 ? '(مدين)' : '') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">لا توجد بيانات مطابقة لمعايير البحث.
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