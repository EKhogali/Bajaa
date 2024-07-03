@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >Ledger</h3>
</div>
<br>
<div class="container row">
    <div class="container col-4">
        <form action="/l_exec?fromdate={{Request('fromdate')}}&todate={{Request('todate')}}&account_id={{Request('account_id')}}" method="get">
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-12">
                    <label for="account_id" class="form-label"><strong>Account</strong></label>
                    <select name="account_id"  class="form-control" >
                        @foreach($accounts as $account)
                            <option value="{{$account->id}}" @if($account->id == $account_id) selected @endif>
                                {{$account->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <br><br><br>

                <div class="col-12">
                    <label for="fromdate" class="form-label"><strong>From Date</strong></label>
                    <input type="date" value="{{$fromdate ?? \Carbon\Carbon::today()}}" class="form-control" id="fromdate" name="fromdate">
                </div>
                <br><br><br>

                <div class="col-12">
                    <label for="todate" class="form-label"><strong>To Date</strong></label>
                    <input type="date" value="{{$todate ?? \Carbon\Carbon::today()}}" class="form-control" id="todate" name="todate">
                </div>
            </div>
            <br>
            <div class="col-12">
                <button type="submit" class="btn btn-primary col mx-auto">Submit</button>
            </div>
{{--            <div class="d-grid gap-2 col mx-auto">--}}
{{--            <div  class="container-fluid row" >--}}
{{--                <a type="submit" class="btn btn-primary" href="/gl_exec" role="button">submit</a>--}}
{{--            </div>--}}
        </form>

{{--        <br>--}}
{{--        <div class="d-grid gap-2 col mx-auto">--}}
{{--            <a class="btn btn-primary" href="journals/create" role="button"  data-bs-toggle="modal" data-bs-target="#addnewjournal">Add Journal 2</a>--}}
{{--        </div>--}}
    </div>
    <div class="container col-8">
        <table class="table">
            <thead>
            <tr>
                {{--                <th scope="col">#</th>--}}
                <th scope="col">Date</th>
{{--                <th scope="col">Account</th>--}}
                <th scope="col">Creditor</th>
                <th scope="col">Debtor</th>
                <th scope="col">Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ledgers as $ledger)
                <tr>
                    {{--                    <th scope="row">{{$ledger->id ?? ''}}</th>--}}
                    <th scope="row">{{\Carbon\Carbon::parse($ledger->date)->format('Y-m-d') ?? ''}}</th>
                    <th scope="row">{{$ledger->name ?? ''}}</th>
                    <th scope="row">{{number_format($ledger->credit_amount,2) ?? '0'}}</th>
                    <th scope="row">{{number_format($ledger->debit_amount,2) ?? '0'}}</th>
                    <th scope="row">{{$ledger->description ?? ''}}</th>

                </tr>
            @endforeach
            </tbody>
        </table>
        <hr><table class="table">
            <thead>
            <tr>
                {{--                <th scope="col">#</th>--}}
                <th scope="col" width="30%"></th>
                {{--                <th scope="col">Account</th>--}}
                <th scope="col" width="21%">{{number_format($total_creditor,2) ?? '0'}}</th>
                <th scope="col">{{number_format($total_debtor,2) ?? '0'}}</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>






</body>
</html>

@endsection

