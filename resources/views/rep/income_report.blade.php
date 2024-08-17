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
        <form action="/income_report?fromdate={{Request('fromdate')}}&todate={{Request('todate')}}" method="get">
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
{{--                    <input type="date" value="{{Request('todate') ?? \Carbon\Carbon::today()->format('d-m-y')}}" class="form-control" id="todate" name="todate">--}}
                </div>
            </div>
            <br>
            <input type="hidden" name="ch" value="1">
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
                <h4>تقرير: الدخل</h4>
                <br>
                <h5>من: {{ request()->get('fromdate') ?? '' }} إلى: {{ request()->get('todate') ?? '' }}</h5>
                <br>
            </div>
        </div>

        <table class="table">
            <tbody>
            <tr>
                <th scope="row" width="4%"></th>
                <th scope="row" width="4%"></th>
                <th scope="row" width="40%">البيــــان</th>
                <th scope="row" width="10%">%</th>
                <th scope="row" width="15%">جزئي</th>
                <th scope="row" width="15%">كلي</th>
                <th scope="row" width="15%">اليومي</th>
                <th scope="row" width="15%">نسبة/اجماليال</th>
                <th scope="row" width="15%">النسبة/مبيعات</th>
            </tr>
            @foreach($income_reports as $income_report)
                @php
                    $ordr1 = $income_report->ordr1 ?? '';
                    $lightBlueBold = ($ordr1 == 0 || $ordr1 == 1);
                    $totalRecord = in_array($ordr1, [7, 9, 12, 15, 17]);
                    $backgroundColor = $totalRecord ? 'background-color: #f0f0f0;' : '';
                    $number1 = $income_report->number1 ?? 0;
                    $number1_2 = $income_report->number1_2 ?? 0;
                    $number2 = $income_report->number2 ?? 0;
                    $number3 = $income_report->number3 ?? 0;
                    $number4 = $income_report->number4 ?? 0;


                     // Determine background color
                    $backgroundColor = '';
                    if (($ordr1 == 0 or $ordr1 == 1) && $number1 <= 0) {
                        $backgroundColor = 'background-color: lightcoral;';
                    } elseif (in_array($ordr1, [7, 10, 13, 14, 17, 18, 20, 23, 25])) {
                        $backgroundColor = 'background-color: #f0f0f0;';
                    } elseif ($ordr1 == 0 || $ordr1 == 1) {
                        $backgroundColor = 'background-color: lightblue; font-weight: bold;';
                    }

                    // Additional styles for total records
                    $additionalStyles = in_array($ordr1, [7, 10, 13, 14, 17, 18, 20, 23, 25]) ? 'text-decoration: underline; font-style: italic;' : '';


                    // Replace zero values with an empty string for focus
                    if($ordr1 != 0){
                        $number1 = $number1 == 0 ? 0 : number_format($number1, $decimal_octets);
                    }
                    $number1_2 = $number1_2 == 0 ? '' : number_format($number1_2, $decimal_octets);
                    $number2 = $number2 == 0 ? 0 : number_format($number2, $decimal_octets);
                    $number3 = $number3 == 0 ? 0 : number_format($number3, $decimal_octets);
                    $number4 = $number4 == 0 ? 0 : number_format($number4, $decimal_octets);

                @endphp
{{--                <tr style="{{ $backgroundColor }} {{ $lightBlueBold ? 'background-color: lightblue; font-weight: bold;' : '' }} {{ $totalRecord ? 'text-decoration: underline; font-style: italic;' : '' }}">--}}
                <tr style="{{ $backgroundColor }} {{ $additionalStyles }}">
                <th scope="row" width="4%">{{ $income_report->id ?? '' }}</th>
                    <th scope="row" width="4%">{{ $ordr1 }}</th>
                    <th scope="row" width="40%">{{ $income_report->txt ?? '' }}</th>
                    <th scope="row" width="10%">{{ $income_report->currency ?? '' }}</th>
                    <th scope="row" width="15%">{{ $number1 }}</th>
                    <th scope="row" width="15%">{{ $number1_2 }}</th>
                    <th scope="row" width="15%">{{ $number2 }}</th>
                    @if($ordr1 == 9 or $ordr1 == 10 or $ordr1 == 12 or $ordr1 == 13)
                    <th scope="row" width="15%">{{ number_format($number3 ?? 0,2).'%' }}</th>
                    <th scope="row" width="15%">{{ number_format($number4 ?? 0,2).'%' }}</th>
                    @else
                    <th scope="row" width="15%">{{ $number3 }}</th>
                    <th scope="row" width="15%">{{ $number4 }}</th>
                        @endif
                </tr>
            @endforeach
            </tbody>
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

