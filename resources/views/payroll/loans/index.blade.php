@extends('layout.master')

@section('content')
    <div class="container mt-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary">سجل السُّلف</h3>
            <a href="{{ route('loan_headers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> إضافة سُلفة جديدة
            </a>
        </div>

        <!-- Loan Headers Table -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">تفاصيل السلف</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive text-center">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>المبلغ</th>
                        <th>عدد الأشهر</th>
                        <th>الخصم الشهري</th>
                        <th>أول شهر</th>
                        <th>الأقساط المتبقية</th>
                        <th>المبلغ المتبقي</th>
                        <th>الوصف</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody id="loan-headers-table">
                    @forelse($loanHeaders as $loanHeader)
                        @php
                            $paidInstallments = $loanHeader->loanDetails->where('done', true)->count();
                            $remainingInstallments = $loanHeader->months - $paidInstallments;
                            $remainingAmount = $loanHeader->amount - ($paidInstallments * ($loanHeader->amount / $loanHeader->months));
                        @endphp
                        <tr>
                            <td>{{ $loanHeader->id }}</td>
                            <td>{{ $loanHeader->employee->name }}</td>
                            <td class="text-danger">{{ number_format($loanHeader->amount, 2) }} </td>
                            <td>{{ $loanHeader->months }}</td>
                            <td>{{ number_format($loanHeader->amount / ($loanHeader->months ?: 1), 2) }} </td>
                            <td>{{ sprintf('%04d-%02d', $loanHeader->start_year, $loanHeader->start_month) }}</td>
                            <td>{{ $remainingInstallments }} شهر</td>
                            <td class="text-warning">{{ number_format($remainingAmount, 2) }} </td>
                            <td>{{ $loanHeader->descrpt }}</td>
                            <td>
                                <a href="{{ route('loan_headers.show', $loanHeader->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> عرض
                                </a>
                                <a href="{{ route('loan_headers.edit', $loanHeader->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> تعديل
                                </a>
                                <form action="{{ route('loan_headers.destroy', $loanHeader->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">لا توجد سلف مسجلة حاليًا</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterByMonth(month) {
            const url = `{{ url('/loan_headers') }}?month=${month}`;
            window.location.href = url;
        }
    </script>
@endsection
