@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >Edit Journal: {{$journalm->id}}</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">
{{--@dd($accounts)--}}
    </div>
    <div class="container col-10">
        <form action="/journals/{{$journalm->id}}" method="POST" >
            {{ csrf_field() }}
            @method('PUT')
{{--            <input type="hidden" name="_method" value="PUT">--}}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="date" class="form-label"><strong>Date</strong></label>
                    <input type="date" class="form-control" id="date" name="date" value="{{\Carbon\Carbon::parse($journalm->date)->format('Y-m-d')}}"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="code" class="form-label"><strong>Code</strong> </label>
                    <input type="text" class="form-control" id="code" name="code" value="{{$journalm->code}}"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col">
                    <label for="description" class="form-label"><strong>Description</strong></label>
                    <input type="textarea" class="form-control" id="description" name="description" value="{{$journalm->description}}"></div>
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

