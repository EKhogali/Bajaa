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

                #printableArea,
                #printableArea * {
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
                <form action="/daily_report?date={{Request('date')}}" method="get">
                    {{ csrf_field() }}
                    <div class="container-fluid row ">

                        <br><br>

                        <div class="col-12">
                            <label for="date" class="form-label"><strong>من:</strong></label>
                            <input type="date"
                                value="{{request()->has('date') ? Request('date') : \Carbon\Carbon::now()->startOfDay('')}}"
                                class="form-control" id="date" name="date">
                        </div>
                        <br><br>

                        <div class="col-12">
                            <label for="date" class="form-label"><strong>الى:</strong></label>
                            <input type="date"
                                value="{{request()->has('date') ? Request('date2') : \Carbon\Carbon::now()->startOfDay('')}}"
                                class="form-control" id="date" name="date2">
                        </div>
                    </div>
                    <br>
                    <input type="hidden" name="ch" value="1">
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
                        <h4>التقرير اليومي</h4>
                        <br>
                        <h5>اليــــوم من: {{ request()->get('date') ?? '' }} إلى {{ request()->get('date2') ?? '' }}</h5>
                        <br>
                    </div>
                </div>

                <table class="table">
                    <tbody>
                        <tr style="background-color: #f2f2f2; font-weight: bold;">
                            <th scope="row" width="4%">#</th>
                            {{-- <th scope="row" width="4%">#</th>--}}
                            <th scope="row" width="40%">البيــــان</th>
                            <th scope="row" width="30%">النسـ (%) ـبة</th>
                            <th scope="row" width="15%">جزئي</th>
                            <th scope="row" width="15%">كلي</th>
                            <th scope="row" width="15%">الربح</th>
                        </tr>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($data_arr as $report)
                            @php $counter += 1; @endphp
                            <tr
                                style="{{ in_array($report['row_id'], [1, 6, 8, 7]) ? 'background-color: #e0e0e0; font-weight: bold; text-decoration: underline;' : '' }}">
                                <th scope="row" width="4%">{{ $counter }}</th>
                                <th scope="row" width="4%">{{ $report['desc'] }}</th>
                                <th scope="row" width="4%">{{ $report['pct'] }}</th>
                                <!-- <th scope="row" width="4%">
                                    {{ is_numeric($report['sub-total']) ? number_format((float) $report['sub-total'], 2, '.', ',') : '' }}
                                </th>
                                <th scope="row" width="4%">
                                    {{ is_numeric($report['total']) ? number_format((float) $report['total'], 2, '.', ',') : '' }}
                                </th>
                                <th scope="row" width="4%">
                                    {{ is_numeric($report['net-total']) ? number_format((float) $report['net-total'], 2, '.', ',') : '' }}
                                </th> -->

                                <th scope="row" width="4%">{{ number_format(floatval($report['sub-total']) ?? 0,2) == 0 ? '' : number_format(floatval($report['sub-total']) ?? 0,2) }}</th>
                                <th scope="row" width="4%">{{ number_format(floatval($report['total']) ?? 0,2) == 0 ? '' : number_format(floatval($report['total']) ?? 0,2) }}</th>
                                <th scope="row" width="4%">{{ number_format(floatval($report['net-total']) ?? 0,2) == 0 ? '' : number_format(floatval($report['net-total']) ?? 0,2) }}</th>
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