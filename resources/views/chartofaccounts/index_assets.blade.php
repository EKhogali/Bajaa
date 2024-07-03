@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>

<div class="container" style="background-color: lightblue;">
    <h3>الأصول الثابتة</h3>
</div>
@if( session()->has('message') )
    @if( session()->has('msgtype') )
        @if( session()->get('msgtype') == 'success' )
            <div class="alert alert-success">
                @elseif(session()->get('msgtype') == 'notsuccess' )
                    <div class="alert alert-danger">
                        @endif
                        @endif
                        {{ session()->get('message') }}
                    </div>
                @endif
<br>
<div class="container row">
    <div class="container col-2">
        <div class="d-grid gap-2 col mx-auto">
{{--            <button class="btn btn-secondary" type="button"><a href="$accounts/create">Add Category</a></button>--}}
            <a class="btn btn-primary" href="accounts/create?acc_type={{1}}" role="button">اضافة حساب</a>
        </div>
    </div>
    <div class="container col-10">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">م.</th>
{{--                <th scope="col">المستوى</th>--}}
                <th scope="col">كود الحساب</th>
                <th scope="col">اسم الحساب</th>
{{--                <th scope="col">فئة الحساب</th>--}}
{{--                <th scope="col">تصنيف الحساب</th>--}}
{{--                <th scope="col">الحساب الرئيسي</th>--}}
                <th scope="col" width="10%">تعديل</th>
                <th scope="col" width="10%">الغاء</th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
                <tr>
                    <th scope="row">{{$account->id ?? ''}}</th>
{{--                    <th scope="row">{{$account->level ?? ''}}</th>--}}
                    <th scope="row">{{$account->code ?? ''}}</th>
                    <th scope="row">{{$account->name ?? ''}}</th>
{{--                    <th scope="row">{{$account->category->name ?? ''}}</th>--}}
{{--                    <th scope="row">--}}
{{--                        @if($account->classification_id == 1)--}}
{{--                            Creditor--}}
{{--                            @elseif($account->classification_id == 2)--}}
{{--                            Debtor--}}
{{--                            @else--}}
{{--                            n/a--}}
{{--                        @endif--}}
{{--                    </th>--}}
{{--                    <th scope="row">{{$account->parentR->name ?? ''}}</th>--}}
                    <th scope="row"><a href="/accounts/{{$account->id}}/edit?acc_type={{Request('acc_type')}}" class="btn btn-warning">تعديل</a></th>
                    <th scope="row"><form method="post" class="delete_form" action="{{action('AccountController@destroy', $account->id)}}">
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

