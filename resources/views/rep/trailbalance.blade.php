@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >Trail Balance | ميزان المراجعة</h3>
</div>
<br>
<div class="container row">
    <div class="container col-3">
        <form action="/tr_exec?frommonth={{Request('frommonth')}}&tomonth={{Request('tomonth')}}" method="get">
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-12">
                    <label for="frommonth" class="form-label"><strong>From Month</strong></label>
                    <input type="number" value="{{Request('frommonth')}}" class="form-control" id="frommonth" name="frommonth">
                </div>
                <br><br><br>

                <div class="col-12">
                    <label for="tomonth" class="form-label"><strong>To Month</strong></label>
                    <input type="number" value="{{Request('tomonth')}}" class="form-control" id="tomonth" name="tomonth">
                </div>
            </div>
            <br>
            <div class="col-12">
                <button type="submit" class="btn btn-primary col mx-auto">Submit</button>
            </div>

        </form>


    </div>
    <div class="container col-9">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Order</th>
                <th scope="col">Level</th>
                <th scope="col">Account ID</th>
                <th scope="col">Account Name</th>
                <th scope="col">Previous Balance</th>
                <th scope="col">Total Creditor</th>
                <th scope="col">Total Debtor</th>
                <th scope="col">Current Balance</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trailbalances as $trailbalance)
                <tr>
                    <th scope="row">{{$trailbalance->order ?? ''}}</th>
                    <th scope="row">{{$trailbalance->level ?? ''}}</th>
                    <th scope="row">{{$trailbalance->account_id ?? ''}}</th>
                    <th scope="row">{{$trailbalance->account_name ?? ''}}</th>
                    <th scope="row">{{number_format(0,2) ?? '0'}}</th>
                    <th scope="row">{{number_format($trailbalance->total_creditor,2) ?? '0'}}</th>
                    <th scope="row">{{number_format($trailbalance->total_debtor,2) ?? '0'}}</th>
                    <th scope="row">{{number_format(0,2) ?? '0'}}</th>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>






</body>
</html>

@endsection

