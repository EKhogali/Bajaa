@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>كشف المرتب</h3>
        <h5>تفاصيل المرتب لشهر {{ \Carbon\Carbon::parse("{$year}-{$month}")->format('F Y') }}</h5>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4>الموظف: {{ $employee->name }}</h4>
                <p><strong>الوظيفة:</strong> {{ $employee->job->name }}</p>
                <p><strong>القسم:</strong> {{ $employee->department->name }}</p>
                <p><strong>الراتب الأساسي:</strong> {{ number_format($employee->basic_salary, 2) }} جنيه</p>
            </div>
        </div>


        <div class="col-md-6 mt-4">
            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>الوصف</th>
                    <th>القيمة</th>
                    <th>اجمالي جزئي</th>
                    <th>الاجمالي الكلي</th>
                </tr>
                </thead>
                <tbody>
                <tr bgcolor="#f5f5f5">
                    <td colspan="4"><strong>الاضافات الثابتة</strong></td>
                </tr>
                @foreach($constantAdditions as $addition)
                    <tr>
                        <td>{{ $addition->description ?? $addition->payrollItemType->name }}</td>
                        <td>{{ number_format($addition->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="2" class="font-weight-bold"><strong>اجمالي الاضافات الثابتة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalConstantAdditions,2)}}</td>
                    <td></td>
                </tr>

                <tr bgcolor="#f5f5f5">
                    <td colspan="4"><strong>الاضافات المتغيرة</strong></td>
                </tr>
                @foreach($changedAdditions as $addition)
                    <tr>
                        <td>{{ $addition->description ?? $addition->payrollItemType->name }}</td>
                        <td>{{ number_format($addition->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="2" class="font-weight-bold"><strong>اجمالي الاضافات المتغيرة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalChangedAdditions,2)}}</td>
                    <td></td>
                </tr>
                <tr class="table-warning" >
                    <td colspan="3" class="font-weight-bold text-center"><strong>اجمالي الاضافات</strong></td>
                    <td class="text-danger font-weight-bold"><strong>{{number_format($totalChangedAdditions + $totalConstantAdditions,2)}}</strong></td>
                </tr>

                <tr bgcolor="#f5f5f5">
                    <td colspan="4"><strong>الاستقطاعات الثابتة</strong></td>
                </tr>
                @foreach($constantDeductions as $deduction)
                    <tr>
                        <td>{{ $deduction->description ?? $deduction->payrollItemType->name }}</td>
                        <td>-{{ number_format($deduction->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="2" class="font-weight-bold"><strong>اجمالي الاستقطاعات الثابتة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalConstantDeductions,2)}}</td>
                    <td></td>
                </tr>


                <tr bgcolor="#f5f5f5">
                    <td colspan="4"><strong>الاستقطاعات المتغيرة</strong></td>
                </tr>

                <!-- Changed Deductions -->
                @foreach($changedDeductions as $deduction)
                    <tr>
                        <td>{{ $deduction->description ?? $deduction->payrollItemType->name }}</td>
                        <td>-{{ number_format($deduction->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="2" class="font-weight-bold"><strong>اجمالي الاستقطاعات المتغيرة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalChangedDeductions,2)}}</td>
                    <td></td>
                </tr>
                <tr class="table-warning" >
                    <td colspan="3" class="font-weight-bold text-center"><strong>اجمالي الاستقطاعات</strong></td>
                    <td class="text-danger font-weight-bold"><strong>{{number_format($totalConstantDeductions + $totalChangedDeductions,2)}}</strong></td>
                </tr>

                <!-- Totals -->
                {{--            <tr class="table-warning">--}}
                {{--                <td colspan="2" class="text-center"><strong>المجموع</strong></td>--}}
                {{--                <td class="text-danger"><strong>{{ number_format($totalConstantAdditions + $totalChangedAdditions, 2) }}</strong></td>--}}
                {{--                <td class="text-danger"><strong>-{{ number_format($totalConstantDeductions + $totalChangedDeductions, 2) }}</strong></td>--}}
                {{--            </tr>--}}

                <!-- Due Amount -->
                <tr class="table-success">
                    <td colspan="3" class="text-center"><strong>المبلغ المستحق</strong></td>
                    <td class="text-success"><strong>{{ number_format($dueAmount, 2) }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('payroll.generate') }}" class="btn btn-secondary">رجوع</a>
            <a href="#" class="btn btn-primary" onclick="window.print()">طباعة</a>
        </div>
    </div>
@endsection


