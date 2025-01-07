@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>إضافة معاملة مالية جديدة</h3>
        <form action="{{ route('payroll_transaction.store') }}" method="POST">
            @csrf
            <div class="col-2">
            <div class="mb-3">
                <label for="month" class="form-label">الشهر</label>
                <input type="month" class="form-control" id="month" name="month" value="{{ now()->format('Y-m') }}" required>
            </div>
            </div>
            <div class="col-4">
            <div class="mb-3">
                <label for="employee_id" class="form-label">الموظف</label>
                <select class="form-select" id="employee_id" name="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="col-2">
            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            </div>
            <div class="col-2">
            <div class="mb-3">
                <label for="payroll_item_type_id" class="form-label">نوع البند</label>
                <select class="form-select" id="payroll_item_type_id" name="payroll_item_type_id" required>
                    @foreach($payrollItemTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="col-">
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <input type="text" class="form-control" id="notes" name="notes" required>
            </div>
            </div>
            <button type="submit" class="btn btn-primary">حفــظ</button>
        </form>
    </div>
@endsection
