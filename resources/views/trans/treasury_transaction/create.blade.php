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
@if(Request('trans_type') == 0)
    <div class="container">
        <h3 >ايصال قبض جديد</h3>
    </div>
@else
    <div class="container">
        <h3 >ايصال صرف جديد</h3>
    </div>
@endif
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
        <form action="/treasury_transaction" method="POST" >
            {{ csrf_field() }}

            <input type="text" name="trans_type" value="{{Request('trans_type')}}" hidden>

{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-3">--}}
{{--                    <label for="name" class="form-label">الرقم الدفتري</label>--}}
{{--                    <input type="text" class="form-control" id="manual_id" name="manual_id">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <br>--}}
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
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="account_id">الخزينة</label>--}}
{{--                    <select class="form-control" id="treasury_id" name="treasury_id" required>--}}
{{--                        <option value="">اختر الخزينة</option>--}}
{{--                        @foreach($treasuries as $treasury)--}}
{{--                            <option value="{{ $treasury->id }}">{{ $treasury->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <br>--}}
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
            <div class="container-fluid row">
                <div class="col-10">
{{--                    <label for="tag_id" class="form-label">الفئة</label>--}}
                    <div>
                        <input type="radio" name="tag_id" value="1">
                        <label for="tag_id">مسحوبات شخصية</label>
                    </div>
                    <div>
                        <input type="radio" name="tag_id" value="0">
                        <label for="tag_id">غير ذلك</label>
                    </div>
                </div>
            </div>
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

