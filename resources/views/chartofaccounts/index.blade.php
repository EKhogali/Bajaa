@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="ar">

<body>
<br>
{{--<div class="container">--}}
{{--    <h3 >دليل الحسابات | Chart of Accounts</h3>--}}
{{--</div>--}}
<div class="container" style="background-color: lightblue;">
    <h3 >دليل الحسابات </h3>
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
            <a class="btn btn-primary" href="/accounts/create?acc_type={{Request('acc_type')}}" role="button">اضافة حساب</a>
        </div>
        <br>
        <form action="/accounts" method="get">
            <input type="hidden" name="is_search" value="true">
            <input type="hidden" name="acc_type" value="0">
                <label for="account_id" class="form-label"><strong>البحث عن حساب</strong></label>
                <select class="form-control" id="account_id" name="account_id" required>
                    @foreach($search_accounts as $search_account)
                        <option value="{{$search_account->id}}" >
                            {{$search_account->name}}
                        </option>
                    @endforeach
                </select>
            <button type="submit" name="filter" id="filter" class="btn btn-primary">بحث</button>
        </form>
    </div>
    <div class="container col-10">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">م.</th>
{{--                <th scope="col">المستوى</th>--}}
                <th scope="col" width="7%">كود الحساب</th>
                <th scope="col">اسم الحساب</th>
{{--                <th scope="col">فئة الحساب</th>--}}
                <th scope="col">تصنيف الحساب</th>
                <th scope="col">تصنيف المشتريات</th>
                <th scope="col">تصنيف الحساب</th>
                <th scope="col">تقرير</th>
{{--                <th scope="col">الحساب الرئيسي</th>--}}
                <th scope="col" width="7%">تعديل</th>
                <th scope="col" width="7%">الغاء</th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
                <tr>
{{--                    <th scope="row">{{$account->archived ?? ''}}</th>--}}
                    <th scope="row">{{$account->id ?? ''}}</th>
{{--                    <th scope="row">{{$account->level ?? ''}}</th>--}}
                    <th scope="row">{{$account->code ?? ''}}</th>
                    <th scope="row">{{$account->name ?? ''}}</th>
                    <th scope="row">{{$account->category->name ?? ''}}</th>
                    <th scope="row">{{$account->categorytxt2 ?? ''}}</th>
                    <th scope="row">{{$account->classification->name ?? ''}}</th>
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
                    <th scope="row">
                        @if($account->show_in_daily_report == 1)
                            اظهار
                        @else
                            /
                        @endif
                    </th>
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

                    <script>
                        $(document).ready(function() {
                            $('#account_id').select2({
                                placeholder: 'اختر الحساب',
                                width: '100%' // Adjust the width as needed
                            });
                        });
                    </script>

</body>
</html>
@endsection

