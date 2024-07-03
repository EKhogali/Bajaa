@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">
<style>
    body{
        /*background-image: url('images\bg03.jpg');*/
        background-repeat: no-repeat;
        background-position-x: 0;
        background-position-y: 0;
        background-size: 100%;
    }
</style>
{{--<body background="images\la2.jpg" >--}}
<body  >
<br>
<div class="container">
    <h3 >اعدادات النظام</h3>
</div>
<br>
<div class="container-fluid row" >
    <div class="container col-4"></div>
    <div class="container col-4">
        <img src="\images\sitting.png" alt="">
    </div>
    <div class="container col-4"></div>
</div>
<br>
<div class="container row">
    <div class="container col-2"></div>
    <div class="container col-8">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>م.</th>
                <th width="30%">اسم الحساب</th>
                <th>رقم الحساب</th>
                <th width="60%">اسم الحساب</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>حساب فائض الخزينة</td>
                <td>{{ $sitting->Cashbox_Faaed_Account }}</td>
                <td>{{ $sitting->CashboxFaaedAccount->name ?? ''  }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>حساب عجز الخزينة</td>
                <td>{{ $sitting->Cashbox_Ajz_Account }}</td>
                <td>{{ $sitting->CashboxAjzAccount->name ?? ''  }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>حساب ايرادات اخرى</td>
                <td>{{ $sitting->Other_Incom }}</td>
                <td>{{ $sitting->OtherIncom->name ?? '' }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>مجموعة مصروفات التشغيل</td>
                <td>{{ $sitting->operation_accounts_category }}</td>
                <td>{{ $sitting->OperationAccount->name ?? ''  }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>مجموعة المصروفات الادارية</td>
                <td>{{ $sitting->administrative_accounts_category }}</td>
                <td>{{ $sitting->AdministrativeAccount->name ?? ''  }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>مجموعة حسابات الديون</td>
                <td>{{ $sitting->dioon_account_category }}</td>
                <td>{{ $sitting->DioonAccountCategory->name ?? ''  }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>مجموعة حسابات مسحوبات من صافي الدخل</td>
                <td>{{ $sitting->pulled_from_net_income_accounts_category }}</td>
                <td>{{ $sitting->PulledFromNetIncomeAccountsCategory->name ?? ''  }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>عدد الخانات العشرية</td>
                <td>{{ $sitting->decimal_octets }}</td>
            </tr>
            <tr style="background-color: #38c172">
                <td></td>
                <td></td>
                <td align="center"> <strong> <a href="sitting/{{$sitting->id}}/edit"> تعديل</a> </strong> </td>
            </tr>
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td>{{ $sitting->id }}</td>--}}
{{--            </tr>--}}
            </tbody>
        </table>
    </div>
    <div class="container col-2"></div>
</div>
<br>
<div class="container row">
    <div class="container col-4"></div>
    <div class="container col-4">


    </div>
    <div class="container col-4"></div>
</div>
<br>
<div class="container row">
    <div class="container col-4"></div>
    <div class="container col-4"></div>
    <div class="container col-4"></div>
</div>






</body>
</html>

@endsection

