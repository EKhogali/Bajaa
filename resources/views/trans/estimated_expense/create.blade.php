@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">
<head>
    <!-- Include Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

</head>
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

    <div class="container">
        <h3 >ايصال صرف جديد</h3>
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
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/estimated_expense" method="POST" >
            {{ csrf_field() }}

            <input type="text" name="transaction_type_id" value="{{Request('transaction_type_id')}}" hidden>

            <div class="container-fluid row ">
                <div class="col-3">
                    <label for="name" class="form-label">التاريخ</label>
                    <input type="date" class="form-control" id="date" name="date" min="{{session('fy_startdate')}} max="{{session('fy_enddate')}}">
                </div>
            </div>
            <br>
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
            </div>
            <br>

            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="amount" class="form-label">القيمة</label>
                    <input type="text" class="form-control" id="amount" name="amount">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-10">
                    <label for="description" class="form-label">البيان</label>
                    <input type="text" class="form-control" id="description" name="description">
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



        <script>
            $(document).ready(function() {
                $('#account_id').select2({
                    placeholder: "اختر الحساب",
                    allowClear: true, // Optional: Clearable selection
                    dir: "rtl" // Optional: Right-to-left text support
                });
            });
        </script>
</body>
</html>
@endsection

