@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">كشف حركات الموردين المالية</h1>
            </div>
        </div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show text-right" role="alert">
                        {{ session()->get('success') }}
                        <button type="button" class="close float-left" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title text-right font-weight-bold m-0" style="flex: 1;">
                            <i class="fas fa-exchange-alt ml-2 text-primary"></i> السجل العام للعمليات المالية
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('transactions.create') }}" class="btn btn-success font-weight-bold shadow-sm">
                                <i class="fas fa-plus ml-1"></i> تسجيل حركة مالية جديدة
                            </a>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped text-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>التاريخ</th>
                                    <th>اسم المورد</th>
                                    <th>مدين (+)</th>
                                    <th>دائن (-)</th>
                                    <th>البيان / الوصف</th>
                                    <th>الوسوم</th>
                                    <th style="width: 180px;">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                                        <td class="font-weight-bold text-primary">{{ $transaction->vendor->name ?? 'مورد محذوف' }}</td>
                                        <td class="text-success font-weight-bold">{{ $transaction->debit > 0 ? number_format($transaction->debit, 2) : '---' }}</td>
                                        <td class="text-danger font-weight-bold">{{ $transaction->credit > 0 ? number_format($transaction->credit, 2) : '---' }}</td>
                                        <td class="text-muted text-right px-3">{{ $transaction->description ?? '---' }}</td>
                                        <td>
                                            @forelse($transaction->tags as $tag)
                                                <span class="badge badge-warning px-2 py-1 ml-1 text-dark font-weight-bold">
                                                    {{ $tag->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">---</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning ml-1" title="تعديل">
                                                <i class="fas fa-edit ml-1"></i> تعديل
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحركة المالية نهائياً؟ سيتم تعديل رصيد المورد تلقائياً.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash ml-1"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-folder-open d-block mb-2 fa-2x text-secondary"></i>
                                            لا توجد أي حركات مالية مسجلة حالياً.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection