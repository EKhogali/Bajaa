@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>كشوف المرتبات الشهرية</h3>

        <form method="GET" action="{{ route('payroll.generate') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="month" class="form-label">الشهر</label>
                    <input type="month" class="form-control" id="month" name="month" value="{{ sprintf('%04d-%02d', $year, $month) }}" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">عرض</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>#</th>
                <th>رمز الموظف</th>
                <th>الاسم</th>
                <th>الوظيفة</th>
                <th>القسم</th>
                <th>الراتب الأساسي</th>
                <th>الاضافات الثابتة</th>
                <th>الاضافات المتغيرة</th>
                <th>الاستقطاعات الثابتة</th>
                <th>الاستقطاعات المتغيرة</th>
                <th>المبلغ المستحق</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payrollData as $data)
                <tr>
                    <td>{{ $year }}</td>
                    <td>{{ $month }}</td>
                    <td>{{ $data['code'] }}</td>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['job'] }}</td>
                    <td>{{ $data['department'] }}</td>
                    <td>{{ number_format($data['basic_salary'], 2) }}</td>
                    <td>{{ number_format($data['const_additions'], 2) }}</td>
                    <td>{{ number_format($data['changed_additions'], 2) }}</td>
                    <td>{{ number_format($data['const_deductions'], 2) }}</td>
                    <td>{{ number_format($data['changed_deductions'], 2) }}</td>
                    <td>
                        <a href="{{ route('payroll.showSlip', ['employee_id' => $data['employee_id'], 'year' => $year, 'month' => $month]) }}" class="btn btn-link">
                            {{ number_format($data['due_amount'], 2) }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            <a href="{{ route('payroll.printAll', ['year' => $year, 'month' => $month]) }}" class="btn btn-success">طباعة جميع الإيصالات</a>
        </div>
    </div>
@endsection
