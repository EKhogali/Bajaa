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
        <form action="/category_percentage_report?fromdate={{Request('fromdate')}}&todate={{Request('todate')}}" method="get">
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
                <h4>تقرير: نسبة المصروفات من اجمالي المبيعات</h4>
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
{{--                <th scope="row" width="10%">%</th>--}}
                <th scope="row" width="15%">العملة</th>
                <th scope="row" width="15%">الاستهلاك الشهري</th>
                <th scope="row" width="15%">الاستهلاك اليومي</th>
                <th scope="row" width="15%">الكمية</th>
                <th scope="row" width="15%">الوحدة</th>
                <th scope="row" width="15%">النسبة%</th>
            </tr>
            @foreach($reports as $report)

                @php
                    $isStartRecord = in_array($report->ordr1, [1, 2, 3, 4, 7, 10, 16, 19]);
                    $isEndRecord = in_array($report->ordr1, [6, 9, 13, 18, 21]);

                //if(in_array($report->ordr1,[5,6])){ $total_pct = $reports->where('ordr1',5)->sum('number1'); }
                //elseif(in_array($report->ordr1,[8,9])){ $total_pct = $reports->where('ordr1',8)->sum('number1'); }
                //elseif(in_array($report->ordr1,[11,12])){ $total_pct = $reports->where('ordr1',11)->sum('number1'); }
                //elseif(in_array($report->ordr1,[17,18])){ $total_pct = $reports->where('ordr1',17)->sum('number1'); }
                //elseif(in_array($report->ordr1,[20,21])){ $total_pct = $reports->where('ordr1',20)->sum('number1'); }
                //else {$total_pct =0;}
                @endphp



                <tr @if($isEndRecord) style="background-color: #ffff8b; text-decoration: underline;"@elseif($isStartRecord) style="background-color: #c0dbfa;" @endif>
                <th scope="row" width="4%">{{ $report->id ?? '' }}</th>
                    <th scope="row" width="4%">{{ $report->ordr1 }}</th>
                    <th scope="row" width="40%">{{ $report->txt ?? '' }}</th>
                    @if(! in_array($report->ordr1, [1,2,3,4,7,10,16,19]))
                        <th scope="row" width="10%">دينار</th>
                    @else
                        <th scope="row" width="10%"></th>
                    @endif
                    <th scope="row" width="15%">{{ $report->number1 == 0 ? '' : number_format($report->number1, $decimal_octets) }}</th>
                    <th scope="row" width="15%">{{ $report->number1_2  == 0 ? '' : number_format($report->number1_2, $decimal_octets) }}</th>
                    <th scope="row" width="15%">{{ $report->number2 == 0 ? '' : $report->number2 }}</th>
{{--                    <th scope="row" width="15%">{{ $report->number3 == 0 ? '' : $report->number3 }}</th>--}}
                    <th scope="row" width="15%">{{ $report->note }}</th>

                    @if(in_array($report->ordr1,[5,6,8,9,11,12,17,18,20,21]))
                        <th scope="row" width="15%">{{ number_format(fdiv($report->number1, $total_pct) * 100,2).'%' }}</th>
                    @else
                        <th scope="row" width="15%"></th>
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

