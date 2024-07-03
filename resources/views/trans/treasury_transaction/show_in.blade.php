@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3>تفاصيل ايصال القبض</h3>
</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<br>
<div class="container">
    <div class="row">
        <div class="col-md-12"> <!-- Empty container takes 30% width -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" width="20%">..</th>
                        <th scope="col">..</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">رقم الايصال</th>
                            <th scope="row">{{$treasury_transaction->id ?? ''}}</th>
                        </tr>
                        <tr>
                            <th scope="row">تاريخ الايصال</th>
                            <th scope="row">{{\Carbon\Carbon::parse($treasury_transaction->date)->format('Y-m-d') ?? ''}}</th>
                        </tr>
                        <tr>
                            <th scope="row">الحساب الرئيسي</th>
                            <th scope="row">{{$treasury_transaction->account->name ?? ''}}</th>
                        </tr>
                        <tr>
                            <th scope="row">القيمة</th>
                            <th scope="row" style="background-color: #ffed4a; color: firebrick;">{{number_format($treasury_transaction->amount, 2) ?? ''}}</th>
                        </tr>
                        <tr>
                            <th scope="row">الخزينة</th>
                            <th scope="row">{{$treasury_transaction->treasury->name ?? ''}}</th>
                        </tr>
                        <tr>
                            <th scope="row">البيان</th>
                            <th scope="row">{{$treasury_transaction->description ?? ''}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</body>
</html>
@endsection
