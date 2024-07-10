@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3>تفاصيل ايصال الصرف</h3>
</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4"> <!-- Empty container takes 30% width -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" width="30%">..</th>
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
                            <th scope="row">القيمة بالحروف</th>
                            <th scope="row" style="background-color: #ffed4a; color: firebrick;">{{\Alkoumi\LaravelArabicTafqeet\Tafqeet::inArabic($treasury_transaction->amount,'ld')}}</th>
                        </tr>
                        <tr>
                            <th scope="row">الخزينة</th>
                            <th scope="row">{{$treasury_transaction->treasury->name ?? ''}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-8"> <!-- Table takes 70% width -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a class="btn btn-primary" href="/treasury_transaction_details/create?master_id={{$treasury_transaction->id}}" role="button">جديد</a>
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
                {{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <br>
                <br>
            </div>

            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">الحساب</th>
                    <th scope="col">الكمية</th>
                    <th scope="col">القيمة</th>
                    <th scope="col">تعديل</th>
                    <th scope="col">الغاء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($treasury_transaction_details as $treasury_transaction_detail)
                    <tr>
                        <th scope="row">{{$treasury_transaction_detail->account->name ?? ''}}</th>
                        <th scope="row">{{$treasury_transaction_detail->qty ?? ''}}</th>
                        <th scope="row" style="background-color: #ffed4a; color: firebrick;">{{ isset($treasury_transaction_detail->amount) ? number_format($treasury_transaction_detail->amount, 2) : '' }}</th>

                        <th scope="row" width="10%"><a href="treasury_transaction_details/{{$treasury_transaction_detail->id}}/edit" class="btn btn-warning">تعديل</a></th>
                        <th scope="row" width="10%">
                            <form method="post" class="delete_form" action="{{action('TreasuryTransactionDetailController@destroy', $treasury_transaction_detail->id)}}">
                                {{csrf_field()}}
                                <input type="hidden" name="_method" value="DELETE" />
                                <button type="submit" class="btn btn-danger">الغاء</button>
                            </form>
                        </th>
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
