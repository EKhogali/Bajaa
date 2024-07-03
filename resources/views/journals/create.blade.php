@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >New Journal</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">
{{--@dd($accounts)--}}
    </div>
    <div class="container col-10">
        <form action="/journals" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="date" class="form-label"><strong>Date</strong></label>
                    <input type="date" class="form-control" id="date" name="date"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="code" class="form-label"><strong>Code</strong> </label>
                    <input type="text" class="form-control" id="code" name="code[]"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col">
                    <label for="description" class="form-label"><strong>Description</strong></label>
                    <input type="textarea" class="form-control" id="description" name="description"></div>
            </div>
            <br>
            <div class="row ">
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

