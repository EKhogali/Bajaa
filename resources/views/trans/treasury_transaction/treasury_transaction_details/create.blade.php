@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >حساب تفصيلي جديد</h3>
</div>
<br>
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
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/treasury_transaction_details" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="account_id">الحساب</label>
                    <select class="form-control" id="account_id" name="account_id" required>
                        <option value="">اختر الحساب</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>

                <br>
                <input type="text" hidden value="{{Request('master_id')}}" name="master_id">

                <div class="container-fluid row">
                    <div class="form-group col-3">
                        <label for="qty">الكمية</label>
                        <input type="text" class="form-control" id="qty" name="qty" required>
                    </div>
                </div>
            <br>
                <div class="container-fluid row">
                <div class="form-group col-3">
                    <label for="amount">القيمة</label>
                    <input type="text" class="form-control" id="amount" name="amount" required>
                </div>
                </div>

                <br>

            <br>
                <div class="container-fluid row">
                <div class="col"></div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
                <div class="col"></div>
            </div>
        </form>


    </div>
</div>

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

