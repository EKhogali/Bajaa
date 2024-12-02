@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >تعديل التصنيف</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/classifications/{{$classifications->id}}" method="post" >
            @method('PUT')
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">مسمى التصنيف</label>
                    <input type="text" value="{{$classifications->name}}" class="form-control" id="name" name="name">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <input type="checkbox" value="{{$classifications->show_in_daily_report}}" id="show_in_daily_report" name="show_in_daily_report">
                    <label for="name" class="form-label">اظهار في التقرير</label>
                </div>
            </div>
            <br>
            <div class="row ">
                <div class="col"></div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">حفــظ</button>
                </div>
                <div class="col"></div>
            </div>
        </form>


    </div>
</div>

</body>
</html>
@endsection

