@extends('layout.master')

@section('content')
    <div class="container mt-5">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary">تفاصيل القرض</h3>
            <a href="{{ route('loan_headers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> العودة إلى القائمة
            </a>
        </div>

        <!-- Session Message -->
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="row">
            <!-- Master Data Section -->
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">بيانات القرض</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>رقم القرض:</th>
                                <td>{{ $loanHeader->id }}</td>
                            </tr>
                            <tr>
                                <th>الموظف:</th>
                                <td>{{ $loanHeader->employee->name }}</td>
                            </tr>
                            <tr>
                                <th>المبلغ الإجمالي:</th>
                                <td>{{ number_format($loanHeader->amount, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <th>عدد الأشهر:</th>
                                <td>{{ $loanHeader->months }}</td>
                            </tr>
                            <tr>
                                <th>أول شهر:</th>
                                <td>{{ sprintf('%04d-%02d', $loanHeader->start_year, $loanHeader->start_month) }}</td>
                            </tr>
                            <tr>
                                <th>الوصف:</th>
                                <td>{{ $loanHeader->descrpt }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">تفاصيل الخصومات</h5>
                        <div>
                            @if($loanHeader->loanDetails->count() < 1)
                            <form action="{{ route('loan_details.generate') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="loan_id" value="{{ $loanHeader->id }}">
                                <button type="submit" class="btn btn-light btn-sm text-primary">
                                    <i class="bi bi-gear-fill"></i> توليد الخصومات الشهرية
                                </button>
                            </form>
                            @endif
                            <button class="btn btn-light btn-sm text-success ms-2" data-bs-toggle="modal" data-bs-target="#addDetailModal">
                                <i class="bi bi-plus-circle"></i> إضافة خصم جديد
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>الشهر/السنة</th>
                                <th>المبلغ</th>
                                <th>تم السداد</th>
                                <th>مؤرشف</th>
                                <th>الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($loanHeader->loanDetails as $detail)
                                <tr>
                                    <td>{{ sprintf('%02d-%04d', $detail->month, $detail->year) }}</td>
                                    <td class="text-center text-danger">
                                        {{ number_format($detail->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $detail->done ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $detail->done ? 'نعم' : 'لا' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $detail->archived ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $detail->archived ? 'نعم' : 'لا' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($detail->done <> 1)
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDetailModal-{{ $detail->id }}">
                                            <i class="bi bi-pencil"></i> تعديل
                                        </button>
                                        <form method="POST" action="{{ route('loan_details.destroy', $detail->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                <i class="bi bi-trash"></i> حذف
                                            </button>
                                        </form>
                                            @endif
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                @include('payroll.loans.edit_detail_modal', ['detail' => $detail])
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">لا توجد تفاصيل</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Detail Modal -->
        @include('payroll.loans.add_detail_modal', ['loanHeader' => $loanHeader])
    </div>
@endsection
