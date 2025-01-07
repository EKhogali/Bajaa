@extends('layout.master')
@section('content')
    <div class="container mt-4">
        @foreach($employees->chunk(6) as $employeeChunk)
            <div class="page">
                @foreach($employeeChunk as $employee)
                    @php
                        $additions = $employee->constantPayrollItems->where('type', 0)->sum('amount')
                                    + $employee->payrollTransactions->where('payroll_item_type_id', 0)->sum('amount');
                        $deductions = $employee->constantPayrollItems->where('type', 1)->sum('amount')
                                    + $employee->payrollTransactions->where('payroll_item_type_id', 1)->sum('amount');
                        $dueAmount = $employee->basic_salary + $additions - $deductions;
                    @endphp
                    <div class="pay-slip">
                        <p><strong>الاسم:</strong> {{ $employee->name }}</p>
                        <p><strong>الوظيفة:</strong> {{ $employee->job->name }}</p>
                        <p><strong>القسم:</strong> {{ $employee->department->name }}</p>
                        <p><strong>الراتب الأساسي:</strong> {{ number_format($employee->basic_salary, 2) }}</p>
                        <p><strong>الإضافات:</strong> {{ number_format($additions, 2) }}</p>
                        <p><strong>الخصومات:</strong> {{ number_format($deductions, 2) }}</p>
                        <p><strong>المبلغ المستحق:</strong> {{ number_format($dueAmount, 2) }}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
