@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >مجموعات الحسابات | Accounts Categories</h3>
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
{{--            <button class="btn btn-secondary" type="button"><a href="categories/create">Add Category</a></button>--}}
            <a class="btn btn-primary" href="categories/create" role="button">اضافة مجموعة</a>
        </div>
    </div>
    <div class="container col-10">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">م.</th>
                <th scope="col">اسم المجموعة</th>
                <th scope="col">تعديل</th>
                <th scope="col">الغاء</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $categories)
                <tr>
                    <th scope="row">{{$categories->id ?? ''}}</th>
                    <th scope="row">{{$categories->name ?? ''}}</th>
                    <th scope="row"><a href="categories/{{$categories->id}}/edit" class="btn btn-warning">تعديل</a></th>
                    <th scope="row"><form method="post" class="delete_form" action="{{action('CategoryController@destroy', $categories->id)}}">
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

