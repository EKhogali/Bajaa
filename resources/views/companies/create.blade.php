@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >شركة جديدة</h3>
</div>
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
        <form action="/companies" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">اسم الشركة</label>
                    <input type="text" class="form-control" id="name" name="name"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="address" class="form-label">العنوان</label>
                    <input type="text" class="form-control" id="address" name="address"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="tel" class="form-label">الهاتف</label>
                    <input type="text" class="form-control" id="tel" name="tel"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="tel" class="form-label">المستخدم</label>
                    <select name="user_id"  class="form-control" >
                        @foreach($users as $user)
                            <option value="{{$user->id}}" >
                                {{$user->name}}
                            </option>
                        @endforeach
                    </select>
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

</body>
</html>
@endsection

