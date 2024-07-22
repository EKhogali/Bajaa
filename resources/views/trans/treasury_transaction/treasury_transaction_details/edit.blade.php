@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >Edit Company {{$company->name}} Details</h3>
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
        <form action="/companies/{{$company->id}}" method="POST" >
            {{ csrf_field() }}
            @method('PUT')
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">Company Name</label>
                    <input type="text" value="{{$company->name}}" class="form-control" id="name" name="name"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="address" class="form-label">Company Address</label>
                    <input type="text" value="{{$company->address}}" class="form-control" id="address" name="address"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="tel" class="form-label">Company Telephone</label>
                    <input type="text" value="{{$company->tel}}" class="form-control" id="tel" name="tel"></div>
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

