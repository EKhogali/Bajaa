@extends('layout.master')
@section('content')
    <div class="container mt-4">
{{--        <h3>كشوف المرتبات الشهرية</h3>--}}
            <div class="row" style="border: 2px solid #6574cd;">
                <div class="col-12 text-center">
                    <h4>{{ session('company_name') }}</h4>
{{--                    <br>--}}
                    <h4>كشف المرتبات</h4>
{{--                    <br>--}}
                    <h5>{{ request('month', sprintf('%04d-%02d', $year, $month)) ?? '' }}</h5>
{{--                    <br>--}}
                </div>
            </div>
        </div>
        <div class="container mt-4">
        <form method="GET" action="{{ route('payroll.generate') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="month" class="form-label">الشهر</label>
                    <input type="month" class="form-control" id="month" name="month"
                           value="{{ old('month', request('month', sprintf('%04d-%02d', $year, $month))) }}" required>

                </div>
                <div class="col-md-3">
                    <label for="department" class="form-label">القسم</label>
                    <select class="form-control" id="department" name="department">
                        <option value="">-- الكل --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">عرض</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead style="background-color: #dcdcdc; font-weight: bold;">
            <tr>
                <th style="background-color: #dcdcdc; font-weight: bold;">##</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">رمز الموظف</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الاسم</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الوظيفة</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">القسم</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الراتب الأساسي</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الاضافات الثابتة</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الاضافات المتغيرة</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الاستقطاعات الثابتة</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">الاستقطاعات المتغيرة</th>
                <th style="background-color: #dcdcdc; font-weight: bold;">المبلغ المستحق</th>
            </tr>
            </thead>
            <tbody>
            @php $cntr = 0; @endphp
            @foreach($payrollData as $data)
                @php $cntr += 1; @endphp
                <tr>
                    <td style="background-color: #dcdcdc; font-weight: bold; text-align: center;">{{ $cntr }}</td>
                    <td>{{ $data['code'] }}</td>
                    <td style="background-color: #f0f8ff; font-weight: bold;">{{ $data['name'] }}</td>
                    <td>{{ $data['job'] }}</td>
                    <td>{{ $data['department'] }}</td>
                    <td style="text-align: center; color: blue;">{{ number_format($data['basic_salary'], 2) }}</td>
                    <td style="text-align: center; color: blue;">{{ number_format($data['const_additions'], 2) }}</td>
                    <td style="text-align: center; color: blue;">{{ number_format($data['changed_additions'], 2) }}</td>
                    <td style="text-align: center; color: blue;">{{ number_format($data['const_deductions'], 2) }}</td>
                    <td style="text-align: center; color: blue;">{{ number_format($data['changed_deductions'], 2) }}</td>
                    <td style="background-color: #f0f8ff; font-weight: bold; color: red; text-align: center;">
                        <a href="{{ route('payroll.showSlip', ['employee_id' => $data['employee_id'], 'year' => $year, 'month' => $month]) }}" class="btn btn-link" style="color: red; text-decoration: none;">
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
