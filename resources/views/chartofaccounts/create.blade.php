@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >حساب جديد</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/accounts" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="code" class="form-label">كود الحساب:</label>
                    <input type="text" class="form-control" id="code" name="code">
                </div>
            </div>
            <br>

            <input hidden name="acc_type" value="{{Request('acc_type')}}">

            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">اسم الحساب</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
            </div>
            <br>
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="classification_id" class="form-label">Acc Classification</label>--}}
{{--                    <p>Acc Classification</p>--}}
{{--                    <input type="radio" id="creditor" name="classification_id" value="1">--}}
{{--                    <label for="creditor">Creditor</label>--}}
{{--                    <input type="radio" id="debtor" name="classification_id" value="2">--}}
{{--                    <label for="debtor">Debtor</label><br><br><br>--}}
{{--            </div>--}}
{{--            <br><br><br>--}}
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="parent_id" class="form-label">Account Parent</label>--}}
{{--                    <select name="parent_id" >--}}
{{--                        <option value="0">none</option>--}}
{{--                        @foreach($parents as $parent)--}}
{{--                            <option value="{{$parent->id}}">--}}
{{--                                {{$parent->name}}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="category_id" class="form-label">مجموعة الحساب</label>
                    <div class="row">
                        <select name="category_id" >
                            {{--                        <option value="0">none</option>--}}
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">
                                    {{$category->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <br>
            <br>
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

