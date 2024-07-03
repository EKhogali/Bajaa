@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >New Journal Details: ({{Request('journalm_id')}})</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">
{{--@dd($accounts)--}}
    </div>
    <div class="container col-10">
{{--        <form action="/journald" method="POST" >--}}
        <form action="/journald" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="account_id" class="form-label"><strong>Account</strong></label>
                    <select name="account_id"  class="form-control" >
                        @foreach($accounts as $account)
                            <option value="{{$account->id}}">
                                {{$account->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="credit_amount" class="form-label"><strong>Credit Amount</strong></label>
                    <input type="number" class="form-control" id="credit_amount" name="credit_amount">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="debit_amount" class="form-label"><strong>Debit Amount</strong></label>
                    <input type="number" class="form-control" id="debit_amount" name="debit_amount">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col">
                    <label for="description" class="form-label"><strong>Description</strong></label>
                    <input type="textarea" class="form-control" id="description" name="description"></div>
            </div>
            <br>
            <div class="row ">

                <input type="text" hidden name="journalm_id" value="{{Request('journalm_id')}}">

                <div class="col"></div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col"></div>
            </div>
        </form>


    </div>
</div>

</body>
</html>
@endsection

