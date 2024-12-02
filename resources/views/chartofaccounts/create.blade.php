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
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="classification_id" class="form-label">تصنيف الحساب</label>
                    <select name="classification_id" class="form-control" >
{{--                        <option value="0">none</option>--}}
                        @foreach($classifications as $classification)
                            <option value="{{$classification->id}}">
                                {{$classification->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="categorytxt2" class="form-label">تصنيف المشتريات</label>--}}
{{--                    <select name="categorytxt2" >--}}
{{--                        @foreach($categorytxt2_list as $categorytxt2)--}}
{{--                            <option value="{{$categorytxt2->categorytxt2}}">--}}
{{--                                {{$categorytxt2->categorytxt2}}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            <br>--}}


{{--            <br>--}}
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="categorytxt2" class="form-label">تصنيف المشتريات</label>--}}
{{--                    <input list="categorytxt2_list" name="categorytxt2" id="categorytxt2" class="form-control" >--}}
{{--                        <datalist id="categorytxt2_list">--}}
{{--                            @foreach($categorytxt2_list as $categorytxt2)--}}
{{--                                <option value="{{$categorytxt2->categorytxt2}}">--}}
{{--                            @endforeach--}}
{{--                        </datalist>--}}
{{--                </div>--}}
{{--            <br>--}}




            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="category_id" class="form-label">مجموعة الحساب</label>
                    <div class="row">
                        <select name="category_id" class="form-control" >
                            {{--                        <option value="0">none</option>--}}
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">
                                    {{$category->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
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

