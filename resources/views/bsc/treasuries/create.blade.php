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
<div class="container">
    <h3 >خزينة جديدة</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/treasuries" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">اسم الخزينة</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
            </div>
            <br>
            <div class="container-fluid row">
                <div class="col-6">
                    <label for="account_id" class="form-label">الحساب</label>
                    <select class="form-select" id="account_id" name="account_id">
                        <option selected disabled>اختر الحساب</option>
                        @foreach($treasury_accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div class="row ">
                <div class="col"></div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">حفــظ</button>
                </div>
                <div class="col"></div>
            </div>
        </form>


    </div>
</div>

</body>
</html>
@endsection

