@extends('layout.master')
@section('content')

    <div class="col-md-6 mt-4">
    <div class="row mb-3 text-center" style="border: 2px solid #6574cd;">
        <div class="col-12">
            <h4>{{ session('company_name') }}</h4>
            <h4>كشف المرتب</h4>
            <h5>تفاصيل المرتب لشهر {{ \Carbon\Carbon::parse("{$year}-{$month}")->format('F Y') }}</h5>
        </div>
    </div>
    </div>
    <div class="container mt-4">

        <div class="row mb-3">
            <div class="col-md-6 mt-4">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th style="background-color: #f5f5f5; width: 20%;">اسم الموظف</th>
                        <td>{{ $employee->name }}</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f5f5f5;">الوظيفة</th>
                        <td>{{ $employee->job->name }}</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f5f5f5;">القسم</th>
                        <td>{{ $employee->department->name }}</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f5f5f5;">الراتب الأساسي</th>
                        <td>{{ number_format($employee->basic_salary, 2) }} جنيه</td>
                    </tr>
                </table>
            </div>
        </div>


        <div class="col-md-6 mt-4">
            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>التصنيف</th>
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
                        <td>{{ $addition->name ?? '' }}</td>
                        <td>{{ $addition->descrpt ?? '' }}</td>
                        <td>{{ number_format($addition->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="3" class="font-weight-bold"><strong>اجمالي الاضافات الثابتة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalConstantAdditions,2)}}</td>
                    <td></td>
                </tr>

                <tr bgcolor="#f5f5f5">
                    <td colspan="5"><strong>الاضافات المتغيرة</strong></td>
                </tr>
                @foreach($changedAdditions as $addition)
                    <tr>
                        <td>{{ $addition->name ?? '' }}</td>
                        <td>{{ $addition->notes ?? '' }}</td>
                        <td>{{ number_format($addition->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="3" class="font-weight-bold"><strong>اجمالي الاضافات المتغيرة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalChangedAdditions,2)}}</td>
                    <td></td>
                </tr>
                <tr class="table-warning" >
                    <td colspan="4" class="font-weight-bold text-center"><strong>اجمالي الاضافات</strong></td>
                    <td class="text-danger font-weight-bold"><strong>{{number_format($totalChangedAdditions + $totalConstantAdditions,2)}}</strong></td>
                </tr>

                <tr bgcolor="#f5f5f5">
                    <td colspan="5"><strong>الاستقطاعات الثابتة</strong></td>
                </tr>
                @foreach($constantDeductions as $deduction)
                    <tr>
                        <td>{{ $deduction->name ?? '' }}</td>
                        <td>{{ $deduction->descrpt ?? '' }}</td>
                        <td>-{{ number_format($deduction->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="3" class="font-weight-bold"><strong>اجمالي الاستقطاعات الثابتة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalConstantDeductions,2)}}</td>
                    <td></td>
                </tr>


                <tr bgcolor="#f5f5f5">
                    <td colspan="5"><strong>الاستقطاعات المتغيرة</strong></td>
                </tr>

                <!-- Changed Deductions -->
                @foreach($changedDeductions as $deduction)
                    <tr>
                        <td>{{ $deduction->name ?? '' }}</td>
                        <td>{{ $deduction->notes ?? '' }}</td>
                        <td>-{{ number_format($deduction->amount, 2) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr class="table-warning" >
                    <td colspan="3" class="font-weight-bold"><strong>اجمالي الاستقطاعات المتغيرة</strong></td>
                    <td class="text-danger font-weight-bold">{{number_format($totalChangedDeductions,2)}}</td>
                    <td></td>
                </tr>
                <tr class="table-warning" >
                    <td colspan="4" class="font-weight-bold text-center"><strong>اجمالي الاستقطاعات</strong></td>
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
                    <td colspan="4" class="text-center"><strong>المبلغ المستحق</strong></td>
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


