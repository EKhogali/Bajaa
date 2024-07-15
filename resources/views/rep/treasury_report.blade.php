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
<div class="container row">
    <div class="container col-3">
        <form action="/treasury_report?fromdate={{Request('fromdate')}}&todate={{Request('todate')}}" method="get">
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-12">
                    <label for="fromdate" class="form-label"><strong>تاريخ بداية الفترة: </strong></label>
                    <input type="date" value="{{request()->has('fromdate') ? Request('fromdate') : \Carbon\Carbon::now()->startOfDay('')}}" class="form-control" id="fromdate" name="fromdate">
{{--                    <input type="date" value="{{Request('fromdate') ?? \Carbon\Carbon::today()->format('d-m-y')}}" class="form-control" id="fromdate" name="fromdate">--}}
                </div>
                <br><br>
                <br><br>

                <div class="col-12">
                    <label for="todate" class="form-label"><strong>تاريخ نهاية الفترة: </strong></label>
                    <input type="date" value="{{request()->has('todate') ? Request('todate') : \Carbon\Carbon::now()->startOfDay('')}}" class="form-control" id="todate" name="todate">
                </div>
            </div>
            <br>
            <div >
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
                <h4>تقرير: الخزينة</h4>
                <br>
                <h5>من: {{ request()->get('fromdate') ?? '' }} إلى: {{ request()->get('todate') ?? '' }}</h5>
                <br>
            </div>
        </div>

        <table class="table">
            <thead style="background-color: #f2f2f2; font-weight: bold;">
            <tr>
                <th scope="row" width="4%" style="text-align: center;"></th>
                <th scope="col" style="text-align: center;">التاريخ</th>
                <th scope="col" style="text-align: center;">صادر/وارد</th>
                <th scope="col" style="text-align: center;">الحساب</th>
                <th scope="col" style="text-align: center;">القيمة</th>
                <th scope="col" style="text-align: center;">الوصف</th>
            </tr>
            </thead>
            <tbody>

            @php
                $tot_out = 0;
                $tot_in = 0;
            @endphp
            @foreach($treasury_report as $report)
                @php

                    if($report->transaction_type_id == 1){
                        $trans_txt = 'صادر';
                        $tot_out += $report->amount;}
                    else{
                        $tot_in += $report->amount;
                    $trans_txt = 'وارد';}

                @endphp

                <tr>
                    <th scope="row">{{$report->id ?? ''}}</th>
{{--                    <th scope="row">{{$report->manual_no ?? ''}}</th>--}}
                    <th scope="row">{{\Carbon\Carbon::parse($report->date)->format('yy-m-d') ?? ''}}</th>
                    <th scope="row"> {{$trans_txt}} </th>
                    <th scope="row">{{$report->account->name ?? ''}}</th>
{{--                    <th scope="row">{{$report->treasury->name ?? ''}}</th>--}}
                    <th scope="row">{{ isset($report->amount) ? number_format($report->amount, $decimal_octets) : '' }}</th>
                    <th scope="row">{{$report->description ?? ''}}</th>
{{--                    <th scope="row">{{$report->tag_id == 1 ? 'مسحوبات' : '/'}}</th>--}}

                </tr>

            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4"></td> <!-- Empty cell for spacing -->
            </tr>
            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;">اجمالي الوارد</td>
                <td colspan="2" style="text-align: center;">{{$tot_in}}</td>
            </tr>
            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;">اجمالي الصادر</td>
                <td colspan="2" style="text-align: center;">{{$tot_out}}</td>
            </tr>
            @php
                $balance = $tot_in - $tot_out;
            @endphp
            <tr style="background-color: #f2f2f2;">
                <td colspan="2" style="font-weight: bold; text-align: center;">الرصيد</td>
                <td colspan="2" style="text-align: center;">{{$balance}}</td>
            </tr>
            </tfoot>

        </table>
        <br><br><br>


    </div>
</div>






<script>
    function printReport() {
        window.print();
    }
</script>

</body>
</html>

@endsection

