@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>تعديل المعاملة المالية</h3>
        <form action="{{ route('payroll_transaction.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="month" class="form-label">الشهر</label>
                <input type="month" class="form-control" id="month" name="month" value="{{ $transaction->year }}-{{ str_pad($transaction->month, 2, '0', STR_PAD_LEFT) }}" required>
            </div>
            <div class="mb-3">
                <label for="employee_id" class="form-label">الموظف</label>
                <select class="form-select" id="employee_id" name="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $transaction->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ</label>
                <input type="number" class="form-control" id="amount" name="amount" value="{{ $transaction->amount }}" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <input type="text" class="form-control" id="notes" name="notes" required value="{{$transaction->notes}}">
            </div>
            <div class="mb-3">
                <label for="payroll_item_type_id" class="form-label">نوع البند</label>
                <select class="form-select" id="payroll_item_type_id" name="payroll_item_type_id" required>
                    @foreach($payrollItemTypes as $type)
                        <option value="{{ $type->id }}" {{ $transaction->payroll_item_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">تعديل</button>
        </form>
    </div>
@endsection
