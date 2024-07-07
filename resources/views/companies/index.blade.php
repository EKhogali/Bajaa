@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >الشركات | المؤسسات</h3>
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
                     <a class="btn btn-primary" href="companies/create" role="button">اضافة شركة</a>
                </div>
                <br><br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">اسم الشركة</th>
                        <th scope="col">العنوان</th>
                        <th scope="col">الهاتف</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">تعديل</th>
{{--                        <th scope="col">delete</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <th scope="row">{{$company->id ?? ''}}</th>
                            <th scope="row">
                                <a href="/companies/{{$company->id}}">
                                    {{$company->name ?? ''}}
                                </a>
                            </th>
                            <th scope="row">{{$company->address ?? ''}}</th>
                            <th scope="row">{{$company->tel ?? ''}}</th>
                            <th scope="row">
                                @if($company->active == 1)
                                    Active
                                @else Inactive
                                @endif
                            </th>
                            <th scope="row"><a href="companies/{{$company->id}}/edit" class="btn btn-warning">Edit</a></th>
{{--                            <th scope="row">--}}
{{--                                <form method="post" class="delete_form" action="{{action('CompanyController@destroy', $company->id)}}">--}}
{{--                                    {{csrf_field()}}--}}
{{--                                    <input type="hidden" name="_method" value="DELETE" />--}}
{{--                                    <button type="submit" class="btn btn-danger">Del</button>--}}
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

