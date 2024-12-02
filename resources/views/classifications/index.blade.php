@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >تصنيفات الحسابت</h3>
</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<br>
<div class="container row">
    <div class="container col-2">
        <div class="d-grid gap-2 col mx-auto">
            <a class="btn btn-primary" href="classifications/create" role="button">اضافة تصنيف</a>
        </div>
    </div>
    <div class="container col-10">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">م.</th>
                <th scope="col">اسم التصنيف</th>
                <th scope="col">اظهار في التقارير</th>
                <th scope="col">تعديل</th>
                <th scope="col">الغاء</th>
            </tr>
            </thead>
            <tbody>
            @foreach($classifications as $classification)
                <tr>
                    <th scope="row">{{$classification->id ?? ''}}</th>
                    <th scope="row">{{$classification->name ?? ''}}</th>
                    <th scope="row">
                        @if($classification->show_in_daily_report == 1)
                            نعم
                        @else
                            لا
                        @endif
                    </th>
                    <th scope="row"><a href="classifications/{{$classification->id}}/edit" class="btn btn-warning">تعديل</a></th>
                    <th scope="row"><form method="post" class="delete_form" action="{{action('ClassificationController@destroy', $classification->id)}}">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="DELETE" />
                            <button type="submit" class="btn btn-danger">الغاء</button>
                        </form></th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
@endsection

