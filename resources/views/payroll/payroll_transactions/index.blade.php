@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>حركات المرتبات</h3>
            <a href="{{ route('payroll_transaction.create') }}" class="btn btn-primary">إضافة معاملة جديدة</a>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="month" class="form-label">الشهر</label>
                <input type="month" class="form-control" id="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" onchange="filterByMonth(this.value)">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
{{--                        <th>السنة</th>--}}
{{--                        <th>الشهر</th>--}}
                        <th width="20%">الموظف</th>
                        <th width="10%">المبلغ</th>
{{--                        <th>الشركة</th>--}}
                        <th width="15%">نوع البند</th>
                        <th>ملاحظات</th>
                        <th width="10%">المستخدم</th>
                        <th width="10%">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody id="transactions-table">
                    @forelse($transactions as $transaction)
                        <tr>
{{--                            <td>{{ $transaction->year }}</td>--}}
{{--                            <td>{{ $transaction->month }}</td>--}}
                            <td>{{ $transaction->employee->name }}</td>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
{{--                            <td>{{ $transaction->company->name }}</td>--}}
                            <td>{{ $transaction->payrollItemType->name }}</td>
                            <td>{{ $transaction->notes }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>
                                <a href="{{ route('payroll_transaction.edit', $transaction->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('payroll_transaction.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد معاملات للشهر المحدد</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterByMonth(month) {
            const url = `{{ url('/payroll_transactions') }}?month=${month}`;
            window.location.href = url;
        }
    </script>
@endsection
