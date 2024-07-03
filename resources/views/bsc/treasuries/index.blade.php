@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >Treasuries | الخزائن</h3>
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
            <a class="btn btn-primary" href="treasuries/create" role="button">اضافة خزينة جديدة</a>
        </div>
    </div>
    <div class="container col-10">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">م.</th>
                <th scope="col">الخزينة</th>
{{--                <th scope="col">رقم حسابها</th>--}}
{{--                <th scope="col">اسم حسابها</th>--}}
                <th scope="col" width="10%">تعديل</th>
                <th scope="col" width="10%">الغاء</th>
            </tr>
            </thead>
            <tbody>
            @foreach($treasuries as $treasury)
                <tr>
                    <th scope="row">{{$treasury->id ?? ''}}</th>
                    <th scope="row">{{$treasury->name ?? ''}}</th>
{{--                    <th scope="row">{{$treasury->account->code ?? ''}}</th>--}}
{{--                    <th scope="row">{{$treasury->account->name ?? ''}}</th>--}}
                    <th scope="row"><a href="treasuries/{{$treasury->id}}/edit" class="btn btn-warning">تعديل</a></th>
                    <th scope="row"><form method="post" class="delete_form" action="{{action('TreasuryController@destroy', $treasury->id)}}">
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

