@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >الشركاء</h3>
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
                     <a class="btn btn-primary" href="partners/create" role="button">اضافة شريك</a>
                </div>
                <br><br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">اسم الشريك</th>
                        <th scope="col">نوع الشراكة</th>
                        <th scope="col">النسبة%</th>
                        <th scope="col">الرقم المالي</th>
                        <th scope="col">الحساب</th>
                        <th scope="col">تعديل</th>
                        <th scope="col">الغاء</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($partners as $partner)
                        <tr>
                            <th scope="row">{{$partner->id ?? ''}}</th>
                            <th scope="row">{{$partner->name ?? ''}}</th>
                            <th scope="row">{{$partner->partnership_type ?? ''}}</th>
                            <th scope="row">{{$partner->win_percentage ?? ''}}</th>
                            <th scope="row">{{$partner->account->name ?? ''}}</th>
                            <th scope="row">{{$partner->accounts->name ?? ''}}</th>
                            <th scope="row"><a href="partners/{{$partner->id}}/edit" class="btn btn-warning">تعديل</a></th>
                            <th scope="row">
                                <form method="post" class="delete_form" action="{{action('PartnerController@destroy', $partner->id)}}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <button type="submit" class="btn btn-danger">الغاء</button>
                                </form>
                            </th>
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

