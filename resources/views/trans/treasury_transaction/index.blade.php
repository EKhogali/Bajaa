@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
@if(request('trans_type') == 0)
<div class="container" style="background-color: lightgreen;">
    <h3>ايصالات القبض</h3>
</div>
@else
    <div class="container" style="background-color: lightcoral;">
        <h3>ايصالات الصرف</h3>
    </div>
@endif
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
                @if(request('trans_type') == 0)
                    <a class="btn btn-primary" href="treasury_transaction/create?trans_type={{0}}" role="button">ايصال قبض جديد</a>
                @else
                    <a class="btn btn-primary" href="treasury_transaction/create?trans_type={{1}}" role="button">ايصال صرف جديد</a>
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
                    <th scope="col">الرقم اليدوي</th>
                    <th scope="col">التاريخ</th>
                    <th scope="col">الحساب</th>
                    <th scope="col">الخزينة</th>
                    <th scope="col">القيمة</th>
                    <th scope="col">الوصف</th>
                    <th scope="col">وسم</th>
                    @if(request('trans_type') != 0)   <th scope="col">عرض</th>   @endif
                    <th scope="col">صباعة</th>
                    <th scope="col">تعديل</th>
                    <th scope="col">الغاء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($treasury_transaction as $treasury_transact)
                    <tr>
                        <th scope="row">{{$treasury_transact->id ?? ''}}</th>
                        <th scope="row">{{$treasury_transact->manual_no ?? ''}}</th>
                        <th scope="row">{{\Carbon\Carbon::parse($treasury_transact->date)->format('yy-m-d') ?? ''}}</th>
                        <th scope="row">{{$treasury_transact->account->name ?? ''}}</th>
                        <th scope="row">{{$treasury_transact->treasury->name ?? ''}}</th>
                        <th scope="row">{{ isset($treasury_transact->amount) ? number_format($treasury_transact->amount, 2) : '' }}</th>
                        <th scope="row">{{$treasury_transact->description ?? ''}}</th>
                        <th scope="row">{{$treasury_transact->tag_id == 1 ? 'مسحوبات' : '/'}}</th>

                        @if(request('trans_type') != 0)
                            <th scope="row"><a href="treasury_transaction/{{$treasury_transact->id}}/" class="btn btn-primary">عرض</a></th>
                        @else
{{--                            <th scope="row"><a href="treasury_transactions.show_in/{{$treasury_transact->id}}/" class="btn btn-primary">عرض</a></th>--}}
                            <th scope="row"><a href="{{ route('show_in', $treasury_transact->id) }}" class="btn btn-primary">عرض</a></th>
                        @endif

{{--                        <th scope="row"><button class="btn btn-success" onclick="window.print()">طباعة</button></th>--}}
                        <th scope="row"><a href="{{ route('treasury_transaction.print', $treasury_transact->id) }}" class="btn btn-success">طباعة</a></th>

                        <th scope="row"><a href="treasury_transaction/{{$treasury_transact->id}}/edit?trans_type={{request('trans_type')}}" class="btn btn-warning">تعديل</a></th>
                        <th scope="row">
                            <form method="post" class="delete_form" action="{{action('TreasuryTransactionController@destroy', $treasury_transact->id)}}">
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
