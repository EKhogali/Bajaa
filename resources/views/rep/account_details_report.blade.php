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

<div class="container row">
    <div class="container col-4">
        <form action="/account_details_report?fromdate={{Request('fromdate')}}&todate={{Request('todate')}}" method="get">
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <br>
                <br>
                <div class="col-12">
                    <label for="account_id" class="form-label"><strong>الحساب: </strong></label>
                    <select class="form-control" id="account_id" name="account_id" required>
                        <option value="">اختر الحساب</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request()->get('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <br>
                <br>
                <br>
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
            <div >
                <button type="submit" class="btn btn-primary col mx-auto">تنفيـــــذ</button>
            </div>
        </form>
    </div>

    <div class="container col-8" id="printableArea">

        <div class="row">
            <div class="col-12 text-center">
                <h4>{{ session('company_name') }}</h4>
                <h4>تقرير: الحركة التفصيلية لحساب</h4>
                <p>الحساب: {{ $selectedAccountName ?? '' }}</p>
                <p>من: {{ request()->get('fromdate') ?? '' }} إلى: {{ request()->get('todate') ?? '' }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr style="background-color: lightgray;">
                <th scope="col" width="4%">م.</th>
                <th scope="col" width="4%">التاريخ</th>
                <th scope="col" width="4%">الكمية</th>
                <th scope="col" width="4%">القيمة</th>
                <th scope="col" width="4%">الوحدة</th>
            </tr>
            </thead>
            <tbody>
            @php $rec = 0; $qty_total = 0;@endphp
            @foreach($account_details_report as $report)
                @php $rec++; $qty_total += $report->qty; @endphp
                <tr>
                    <th scope="row" width="4%">{{$rec}}</th>
                    <th scope="row" width="4%">{{ \Carbon\Carbon::parse($report->date)->format('Y-m-d') ?? '' }}</th>
                    <th scope="row" width="4%">{{ $report->qty ?? '' }}</th>
                    <th scope="row" width="4%">{{ number_format($report->d_amount,2) ?? '' }}</th>
                    <th scope="row" width="4%">{{ $report->Unit_description ?? '' }}</th>
                </tr>
            @endforeach
            <!-- Totals Row -->
            <tr style="background-color: lightgray; font-weight: bold;">
                <td>الإجمالي</td>
                <td></td>
                <td>{{$qty_total}}</td>
                <td>{{ number_format($total_amount, 2) }}</td>
            </tr>
            </tbody>
        </table>



    </div>
</div>


<br><br>
<div class="container">
    <button onclick="printReport()" class="btn btn-primary">طباعة التقرير</button>
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

