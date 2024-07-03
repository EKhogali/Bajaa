@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >المستخدمون</h3>
</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<br>
    <div class="container row">
            <div class="container col">
                <div class="d-grid gap-2 col-3">
                     <a class="btn btn-primary" href="users/create" role="button">اضافة مستخدم جديد</a>
                </div>
                <br><br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">الصفة</th>
                        <th scope="col">البريد الالكتروني</th>
{{--                        <th scope="col">الشركة</th>--}}
                        <th scope="col" width="10%">edit</th>
{{--                        <th scope="col">delete</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th scope="row">{{$user->id ?? ''}}</th>
                            <th scope="row">{{$user->name ?? ''}}</th>
                            <th scope="row">
                                @if($user->type == 1)     admin
                                @elseif($user->type == 2) supervisor
                                @elseif($user->type == 3) data entry
                                @elseif($user->type == 4) Reporter
                                @endif
                            </th>
                            <th scope="row">{{$user->email ?? ''}}</th>
{{--                            <th scope="row">{{$user->company->name ?? ''}}</th>--}}
                            <th scope="row"><a href="users/{{$user->id}}/edit" class="btn btn-warning">تعديل</a></th>
{{--                            <th scope="row">--}}
{{--                                <form method="post" class="delete_form" action="{{action('UserController@destroy', $user->id)}}">--}}
{{--                                    {{csrf_field()}}--}}
{{--                                    <input type="hidden" name="_method" value="DELETE" />--}}
{{--                                    <button type="submit" class="btn btn-danger">الغاء</button>--}}
{{--                                </form>--}}
{{--                            </th>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>



{{--            <div class="container col-5">--}}


{{--                    --}}{{-- ----------------------------------------------------------------------------------------}}

{{--                <table class="table">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th scope="col">Financial Year</th>--}}
{{--                        <th scope="col">Status</th>--}}
{{--                        <th scope="col">edit</th>--}}
{{--                        <th scope="col">delete</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($financial_years->where('company_id',$company->id) as $financial_year)--}}
{{--                        <tr>--}}
{{--                            <td class="col-2">{{$financial_year->financial_year ?? ''}}</td>--}}
{{--                            <td class="col-2"><a href="">--}}
{{--                                    @if($financial_year->state_id == 1)--}}
{{--                                        Closed--}}
{{--                                    @else--}}
{{--                                        Closed--}}
{{--                                    @endif--}}
{{--                                </a></td>--}}
{{--                            --}}{{--                                <td class="col-2">--}}
{{--                            --}}{{--                                    <a href="financial_years/{{$financial_year->id}}/edit" class="btn btn-warning">Edit</a>--}}
{{--                            --}}{{--                                </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                </table>--}}

{{--            </div>--}}
    </div>


                    {{-- ----------------------------------------------------------------------------------------}}








</body>
</html>
@endsection

