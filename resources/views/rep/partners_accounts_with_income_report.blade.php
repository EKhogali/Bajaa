@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<head>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea, #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
<br>

<br>
<div class="container row" >
    <div class="container col-3" >
        <form action="/partners_accounts_with_income_report?fromdate={{Request('fromdate')}}&todate={{Request('todate')}}" method="get">
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-12">
                    <label for="fromdate" class="form-label"><strong>تاريخ بداية الفترة: </strong></label>
                    <input type="date"
                           value="{{request()->has('fromdate') ? Request('fromdate') : \Carbon\Carbon::now()->startOfDay('')}}"
                           class="form-control" id="fromdate" name="fromdate">
                </div>
                <br><br>
                <br><br>

                <div class="col-12">
                    <label for="todate" class="form-label"><strong>تاريخ نهاية الفترة: </strong></label>
                    <input type="date"
                           value="{{request()->has('todate') ? Request('todate') : \Carbon\Carbon::now()->startOfDay('')}}"
                           class="form-control" id="todate" name="todate">
                </div>
                <br><br>
                <br><br>

                <div class="col-12">
                    <label for="account_id" class="form-label"><strong>الحساب</strong></label>
{{--                    <select name="account_id"  class="form-control" >--}}
                    <select class="form-control" id="account_id" name="account_id" required>
                        @foreach($accounts as $account)
                            <option value="{{$account->id}}" @if($account->id == $account_id) selected @endif>
                                {{$account->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div>
                <button type="submit" class="btn btn-primary col mx-auto">تنفيـــــذ</button>
            </div>
        </form>

        <br><br>
        <div class="container">
            <button onclick="printReport()" class="btn btn-primary">طباعة التقرير</button>
        </div>
    </div>
    <div class="container col-9" id="printableArea">

        <div class="row" style="border: 2px solid #6574cd;">
            <div class="col-12 text-center">
                <h4>{{ session('company_name') }}</h4>
                <br>
                <h4>تقرير: تقرير مسحوبات من صافي الربح وحصص المستثمر والشريك</h4>
                <br>
                <h5>{{$arr['partner_name']}}</h5>
                <br>
                <h5>من: {{ request()->get('fromdate') ?? '' }} إلى: {{ request()->get('todate') ?? '' }}</h5>
                <br>
{{--                <h5> صافي الربح: {{$arr['net_profit']}}</h5>--}}
                <h5> صافي الربح: {{isset($arr['net_profit']) ? number_format($arr['net_profit'], $decimal_octets) : '' }}</h5>
                <br>
{{--                <h5> صافي الربح بعد المسحوبات: {{$arr['title_amount']}}</h5>--}}
                <h5> صافي الربح بعد المسحوبات: {{isset($arr['title_amount']) ? number_format($arr['title_amount'], $decimal_octets) : '' }}</h5>
            </div>
        </div>

        <br><br>
        <table class="table">
            <thead style="background-color: #f2f2f2; font-weight: bold;">
            <tr style="text-align: center; vertical-align: middle; background-color: #f6993f; height: 50px;">
                <td  colspan="5" style="text-align: center; vertical-align: middle; background-color: transparent;">
                    <h5>المسحوبات من صافي الربح</h5>
                </td>
            </tr>
            <tr>
                <th scope="row" width="4%" style="text-align: center;"></th>
                <th scope="col" style="text-align: center;">التاريخ</th>
                <th scope="col" style="text-align: center;">وارد</th>
                <th scope="col" style="text-align: center;">صادر</th>
{{--                <th scope="col" style="text-align: center;">الحساب</th>--}}
{{--                <th scope="col" style="text-align: center;">القيمة</th>--}}
                <th scope="col" style="text-align: center;">الوصف</th>
            </tr>
            </thead>
            <tbody>


            @php
                $tot_out = 0;
                $tot_in = 0;
                $rec_id = 1;
            @endphp
            <tr>
                <th scope="row">{{$rec_id ?? ''}}</th>
                <th scope="row"></th>
                <th scope="row">{{ isset($arr['net_profit']) ? number_format($arr['net_profit'], $decimal_octets) : '' }}</th>
                <th scope="row"></th>
{{--                <th scope="row"></th>--}}
                <th scope="row"> {{'  صافي الربح' }} </th>

            </tr>



            @foreach($reports2 as $report)
                @php
                    $rec_id +=1;

                @endphp

                <tr>
                    <th scope="row">{{$rec_id ?? ''}}</th>
                    <th scope="row">{{\Carbon\Carbon::parse($report->date)->format('yy-m-d') ?? ''}}</th>
{{--                    <th scope="row">  </th>--}}
                    <th scope="row"></th>
                    <th scope="row">{{ isset($report->amount) ? number_format($report->amount, $decimal_octets) : '' }}</th>
                    <th scope="row">{{$report->name ?? ''}}</th>

                </tr>

            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4"></td> <!-- Empty cell for spacing -->
            </tr>

            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;">اجمالي المسحوبات من صافي الربح</td>
                <td colspan="2" style="text-align: center;">{{ isset($reports2) ? number_format($reports2->sum('amount'), $decimal_octets) : '' }}</td>
            </tr>

            </tfoot>

        </table>
        <br><br><br>







        <table class="table">
            <thead style="background-color: #f2f2f2; font-weight: bold;">
            <tr style="text-align: center; vertical-align: middle; background-color: #f6993f; height: 50px;">
                <td  colspan="5" style="text-align: center; vertical-align: middle; background-color: transparent;">
                    <h5>مسحوبات  {{' '.$arr['partnership_type_desc'].' '.$arr['partner_name']}}</h5>
                </td>
            </tr>
            <tr>
                <th scope="row" width="4%" style="text-align: center;"></th>
                <th scope="col" style="text-align: center;">التاريخ</th>
                <th scope="col" style="text-align: center;">وارد</th>
                <th scope="col" style="text-align: center;">صادر</th>
                {{--                <th scope="col" style="text-align: center;">الحساب</th>--}}
                {{--                <th scope="col" style="text-align: center;">القيمة</th>--}}
                <th scope="col" style="text-align: center;">الوصف</th>
            </tr>
            </thead>
            <tbody>


            @php
                $tot_out = 0;
                $tot_in = 0;
                $rec_id = 1;
            @endphp
            <tr>
                <th scope="row">{{$rec_id ?? ''}}</th>
                <th scope="row"></th>
                <th scope="row">{{ isset($arr['profit_pct_amount']) ? number_format($arr['profit_pct_amount'], $decimal_octets) : '' }}</th>
                <th scope="row"></th>
                {{--                <th scope="row"></th>--}}
                <th scope="row"> {{$arr['partner_pct'].' %'.' من صافي الربح' }} {{' ('.isset($arr['title_amount']) ? number_format($arr['title_amount'], $decimal_octets).')' : '' }} </th>

            </tr>


            @php $rec_id = 0;  $tot_in = 0; $tot_out = 0;@endphp
            @foreach($reports as $report)
                @php
                    $rec_id +=1;

                @endphp

                <tr>
                    <th scope="row">{{$rec_id ?? ''}}</th>
                    <th scope="row">{{\Carbon\Carbon::parse($report->created_at)->format('yy-m-d') ?? ''}}</th>
{{--                                        <th scope="row">  </th>--}}
                    <th scope="row"></th>
                    <th scope="row">{{ isset($report->amount) ? number_format($report->amount, $decimal_octets) : '' }}</th>
                    <th scope="row">{{$report->description ?? ''}}</th>

                </tr>

            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4"></td> <!-- Empty cell for spacing -->
            </tr>

            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;">اجمالي الوارد</td>
                <td colspan="2" style="text-align: center;">{{ isset($arr['profit_pct_amount']) ? number_format($arr['profit_pct_amount'], $decimal_octets) : '' }}</td>
            </tr>
            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;">اجمالي المسحوبات</td>
                <td colspan="2" style="text-align: center;">{{ isset($reports) ? number_format($reports->sum('amount'), $decimal_octets) : '' }}</td>
            </tr>
            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;"> الرصيد </td>
                <td colspan="2" style="text-align: center;">{{  number_format($arr['profit_pct_amount'] - $reports->sum('amount'), $decimal_octets) ?? '' }}</td>
            </tr>
            </tfoot>


        </table>


    </div>
</div>


<script>
    function printReport() {
        window.print();
    }
</script>

<script>
    $(document).ready(function() {
        $('#account_id').select2({
            placeholder: 'اختر الحساب',
            width: '100%' // Adjust the width as needed
        });
    });
</script>

</body>
</html>

@endsection

