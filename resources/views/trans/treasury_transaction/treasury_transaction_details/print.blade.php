@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">
<head>
    <style>
        .print-container {
            border: 2px solid #6574cd;
            padding: 15px;
        }
    </style>
    <script>
        function printContainer() {
            var printContent = document.getElementById('print-container').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = '<div>' + printContent + '</div>';
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
</head>
<body>
<br>
<div class="container">

</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<br>
<div class="row" >
    <button class="btn btn-success" onclick="printContainer()">طباعة</button>
</div>
<br>
<div class="container"  id="print-container">

    <div class="row" style="border: 2px solid #6574cd">
        <div class="col-3"></div>
        <div class="col-6">
            <h5>{{session()->get('company_name')}}</h5>
{{--            <br>--}}
            <h5>تفاصيل ايصال الصرف</h5>
{{--            <br>--}}
            <h5>{{\Carbon\Carbon::today()->format('Y-m-d')}}</h5>
            <br>
            <h6>رقم لاايصال: {{$treasury_transactions->manual_no ?? ''}}</h6>
            <h6>تاريخ الايصال: {{\Carbon\Carbon::parse($treasury_transactions->date)->format('Y-m-d') ?? ''}}</h6>
            <h6>الحساب الرئيسي: {{$treasury_transactions->account->name ?? ''}}</h6>
            <h6>قيمة لاايصال: {{$treasury_transactions->amount ?? 0}}</h6>
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row">
{{--        <div class="col-md-2"> <!-- Empty container takes 30% width -->--}}
{{--            <div class="d-grid gap-2 d-md-flex justify-content-md-end">--}}

{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-12"> <!-- Table takes 70% width -->

{{--            <div class="d-grid gap-2 d-md-flex justify-content-md-end">--}}
{{--                <br>--}}
{{--                <br>--}}
{{--            </div>--}}

            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الحساب</th>
                    <th scope="col" width="10%">الكمية</th>
                    <th scope="col" width="10%">القيمة</th>
                    <th scope="col" width="10%">الوحدة</th>
                </tr>
                </thead>
                <tbody>
                @php $rec = 0; @endphp
                @foreach($treasury_transaction_details as $treasury_transaction_detail)
                    @php $rec ++; @endphp
                    <tr>
                        <th scope="row">{{$rec ?? ''}}</th>
                        <th scope="row">{{$treasury_transaction_detail->account->name ?? ''}}</th>
                        <th scope="row">{{$treasury_transaction_detail->qty ?? ''}}</th>
                        <th scope="row" style="background-color: #ffed4a; color: firebrick;">{{ isset($treasury_transaction_detail->amount) ? number_format($treasury_transaction_detail->amount, 2) : '' }}</th>
                        <th scope="row">{{$treasury_transaction_detail->account->Unit_description ?? ''}}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
@endsection
