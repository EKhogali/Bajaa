@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
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
<br>
<div class="container">
    <h3 >تعديل بيانات الايصال رقم:  {{$treasury_transaction->id ?? '//'}} </h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="{{ route('treasury_transaction.update', ['treasury_transaction' => $treasury_transaction->id]) }}" method="POST" >
{{--        <form action="/treasury_transaction/{{$treasury_transaction->id}}" method="POST" >--}}
            {{ csrf_field() }}
            @method('PUT')
            <input type="text" name="trans_type" value="{{Request('trans_type')}}" hidden>

            <div class="container-fluid row ">
                <div class="col-3">
                    <label for="manual_no" class="form-label">الرقم الدفتري</label>
                    <label for="manual_no" class="form-label"> {{$treasury_transaction->manual_no}}</label>
{{--                    <input type="text" class="form-control" id="manual_no" name="manual_no" value="{{$treasury_transaction->manual_no}}">--}}
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-3">
                    <label for="date" class="form-label">التاريخ</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{\Carbon\Carbon::parse($treasury_transaction->date)->format('Y-m-d') ?? Carbon\Carbon::now()->format('Y-m-d')}}">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="account_id">الحساب</label>
                    <select class="form-control" id="account_id" name="account_id" required>
                        <option value="">اختر الحساب</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @if($account->id == $treasury_transaction->account_id) selected @endif>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="treasury_id">الخزينة</label>--}}
{{--                    <select class="form-control" id="treasury_id" name="treasury_id" required>--}}
{{--                        <option value="">اختر الخزينة</option>--}}
{{--                        @foreach($treasuries as $treasury)--}}
{{--                            <option value="{{ $treasury->id }}" @if($treasury->id == $treasury_transaction->treasury_id) selected @endif>--}}
{{--                                {{ $treasury->name }}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <br>--}}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="amount" class="form-label">القيمة</label>
                    <input type="text" class="form-control" id="amount" name="amount" value="{{$treasury_transaction->amount}}">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-10">
                    <label for="description" class="form-label">البيان</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{$treasury_transaction->description}}">
                </div>
            </div>
            <br>
            <div class="container-fluid row">
                <div class="col-10">
                    {{--                    <label for="tag_id" class="form-label">الفئة</label>--}}
                    <div>
                        <input type="radio" name="tag_id" value="1" @if($treasury_transaction->tag_id == 1) checked @endif >
                        <label for="tag_id">مسحوبات شخصية</label>
                    </div>
                        <div>
                            <input type="radio" name="tag_id" value="0">
                            <label for="tag_id">غير ذلك</label>
                        </div>
                </div>
            </div>
            <br>
            <br>
            <div class="row ">
                <div class="col"></div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">حفظ</button>

                </div>
                <div class="col"></div>
            </div>
        </form>


    </div>
</div>

</body>
</html>
@endsection

