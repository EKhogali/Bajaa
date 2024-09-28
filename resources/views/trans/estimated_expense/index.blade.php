@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>

<div class="container" style="background-color: lightgreen;">
    <h3>ايصالات الصرف التقديرية</h3>
</div>
@if( session()->has('message') )
    @if( session()->has('msgtype') )
        @if( session()->get('msgtype') == 'success' )
            <div class="alert alert-success">
                @elseif(session()->get('msgtype') == 'notsuccess' )
                    <div class="alert alert-danger">
                        @endif
                        @endif
                        {{ session()->get('message') }}
                    </div>
                @endif
                <br>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-2"> <!-- Empty container takes 30% width -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                @if(request('transaction_type_id') == 0)
                    <a class="btn btn-primary" href="estimated_expense/create?transaction_type_id={{0}}" role="button">ايصال قبض جديد</a>
                @else
                    <a class="btn btn-primary" href="estimated_expense/create?transaction_type_id={{1}}" role="button">ايصال صرف جديد</a>
                @endif



            </div>
        </div>
        <div class="col-md-10"> <!-- Table takes 70% width -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
{{--                <a class="btn btn-primary" href="treasury_transaction/create" role="button">ايصال قبض جديد</a>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
{{--                <button class="btn btn-secondary mx-2" type="button">...</button>--}}
            </div>
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">رقم آلي</th>
                    <th scope="col">التاريخ</th>
                    <th scope="col">الحساب</th>
                    <th scope="col">القيمة</th>
                    <th scope="col">الوصف</th>
                    <th scope="col">تعديل</th>
                    <th scope="col">الغاء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($estimated_expenses as $estimated_expense)
                    <tr>
                        <th scope="row">{{$estimated_expense->id ?? ''}}</th>
                        <th scope="row" width="10%">{{\Carbon\Carbon::parse($estimated_expense->date)->format('Y-m-d') ?? ''}}</th>
                        <th scope="row">{{$estimated_expense->account->name ?? ''}}</th>
                        <th scope="row">{{ isset($estimated_expense->amount) ? number_format($estimated_expense->amount, 2) : '' }}</th>
                        <th scope="row">{{$estimated_expense->description ?? ''}}</th>
                        <th scope="row" width="5%"><a href="estimated_expense/{{$estimated_expense->id}}/edit?transaction_type_id={{request('transaction_type_id')}}" class="btn btn-warning">تعديل</a></th>
                        <th scope="row" width="5%">
                            <form method="post" class="delete_form" action="{{action('EstimatedExpenseController@destroy', $estimated_expense->id)}}">
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
