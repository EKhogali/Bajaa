@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >Edit Account</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/accounts/{{$account->id}}" method="post" >
            @method('PUT')
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="code" class="form-label">Account Code</label>
                    <input type="text" class="form-control" id="code" name="code" value="{{$account->code}}"></div>
            </div>
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="name" class="form-label">Account Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$account->name}}"></div>
            </div>
            <br>
            <input name="acc_type" value="{{Request('acc_type')}}" hidden type="text">
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="classification_id" class="form-label">Acc Classification</label>--}}
{{--                    <p>Account Classification</p>--}}
{{--                    <input type="radio" id="creditor" name="classification_id"  value="1" @if($account->classification_id == 1) selected @endif                    >--}}
{{--                    <label for="creditor">Creditor</label>--}}
{{--                    <input type="radio" id="debtor" name="classification_id" value="2" @if($account->classification_id == 2) selected @endif>--}}
{{--                    <label for="debtor">Debtor</label><br>--}}
{{--            </div>--}}
{{--            <br><br><br>--}}
{{--            <div class="container-fluid row ">--}}
{{--                <div class="col-6">--}}
{{--                    <label for="parent_id" class="form-label">Account Parent</label>--}}
{{--                    <select name="parent_id" >--}}
{{--                        <option value="0">none</option>--}}
{{--                        @foreach($parents as $parent)--}}
{{--                            <option value="{{$parent->id}}" @if($parent->id == $account->parent_id) selected @endif >--}}
{{--                                {{$parent->name}}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--            </div>--}}
            <br>
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="category_id" class="form-label">Account Category</label>
                    <select name="category_id" >
{{--                        <option value="0">none</option>--}}
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if($category->id == $account->category_id) selected @endif >
                                {{$category->name}}
                            </option>
                        @endforeach
                    </select>
            </div>
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

