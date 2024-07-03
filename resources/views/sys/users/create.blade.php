@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >مستخدم جديد</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/users" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="type" class="form-label">صفة المستخدم</label>
                    <select name="type" id="type" class="form-control">
{{--                        <option value="1">Admin</option>--}}
                        <option value="2">Supervisor</option>
                        <option value="3" selected>Data Entry</option>
                        <option value="4">Reporter</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="email">البريد الالكتروني</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="password">كلمة المرور</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                <label for="company_id">الشركة</label>
                <select name="company_id" id="company_id" class="form-control" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
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

