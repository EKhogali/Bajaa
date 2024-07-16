@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >تعديل بيانات المستخدم:  {{$user->name}} </h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/users/{{$user->id}}" method="POST" >
{{--            <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST">--}}

            {{ csrf_field() }}
            @method('PUT')
            <div class="container-fluid row">
                <div class="col-6">
                    <label for="name" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                </div>
            </div>
            <br>
            <div class="container-fluid row">
                <div class="col-6">
                    <label for="type" class="form-label">صفة المستخدم</label>
                    <select name="type" id="type" class="form-control">
                        <option value="1" {{ $user->type == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ $user->type == 2 ? 'selected' : '' }}>Supervisor</option>
                        <option value="3" {{ $user->type == 3 ? 'selected' : '' }}>Data Entry</option>
                        <option value="4" {{ $user->type == 4 ? 'selected' : '' }}>Reporter</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="container-fluid row">
                <div class="col-6">
                    <label for="email" class="form-label">البريد الالكتروني</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
            </div>
            <br>

{{--            <div class="container-fluid row">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="company_id" class="form-label">الشركة</label>--}}
{{--                    <select name="company_id" id="company_id" class="form-control" required>--}}
{{--                        @foreach($companies as $company)--}}
{{--                            <option value="{{ $company->id }}" {{ $user->company_id == $company->id ? 'selected' : '' }}>--}}
{{--                                {{ $company->name }}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

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

