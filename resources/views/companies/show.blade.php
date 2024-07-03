@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 ><a href="/companies">Company</a> Details</h3>
</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<br>
<div class="container row">
    <div class="container col-4">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Field</th>
                <th scope="col">Value</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Company ID</th>
                    <th scope="row">{{$company->id ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Company Name</th>
                    <th scope="row">{{$company->name ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Address</th>
                    <th scope="row">{{$company->address ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Telephone</th>
                    <th scope="row">{{$company->tel ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Company Status</th>
                    <th>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activemodal">
{{--                            @dd($company->active)--}}
                            @if($company->active == 1)
                                Active
                            @else Inactive
                            @endif
                        </button>


                    </th>
                </tr>
            </tbody>
        </table>


        <div class="d-grid gap-2 col mx-auto">
            {{--            <button class="btn btn-secondary" type="button"><a href="categories/create">Add Category</a></button>--}}
            <a class="btn btn-primary" href="/financial_years/create?company_id={{$company->id}}" role="button">Add Financial Year</a>
        </div>
        <br>
        <div class="d-grid gap-2 col mx-auto">
            <a class="btn btn-primary" href="/companies/create" role="button">Add New Company</a>
        </div>
        <br>
        <div class="row">
{{--            <div class="d-grid gap-2 col-6">--}}
{{--                <a href="/companies/{{$company->id}}/edit" class="btn btn-warning">Edit</a>--}}
{{--            </div>--}}
            <div class="d-grid gap-2 col-6">
                <form method="post" class="delete_form" action="{{action('CompanyController@destroy', $company->id)}}">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="btn btn-danger">Delete Company</button>
                </form>
            </div>
        </div>

    </div>
    <div class="container col-8">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Financial Year</th>
                <th scope="col">Status</th>
{{--                <th scope="col">edit</th>--}}
{{--                <th scope="col">delete</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($financial_years as $financial_year)
                <tr>
                    <th scope="row">{{$financial_year->id ?? ''}}</th>
                    <th scope="row">{{$financial_year->financial_year ?? ''}}</th>
                    <th scope="row">
                        <a href="">
                            @if($financial_year->state_id == 1)
                                Closed
                            @else
                                Closed
                            @endif
                        </a>
                    </th>

                    <th scope="row">
                        <form method="post" class="delete_form" action="{{action('FinancialYearController@destroy', $financial_year->id)}}">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="DELETE" />
                            <button type="submit" class="btn btn-danger">Del</button>
                        </form>
                    </th>
                </tr>

{{-- Modal -------------------------------------------------------------------------}}
                <!-- Button trigger modal -->


                <!-- Modal -->
                <div class="modal fade" id="activemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Company Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="/update_state?company={{$company->id}}" method="POST" >
                                    {{ csrf_field() }}
                                    @method('PUT')
                                    <div class="container-fluid row ">
                                        <div class="container-fluid row ">
                                            <div class="col-1">
                                                <input type="checkbox" id="active" name="active"  @if($company->active == 1) checked @endif  >
                                            </div>
                                            <div class="col">
                                                <label for="active" class="form-label">Active / Inactive</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>






                        </div>
                    </div>
                </div>
{{-- Modal -------------------------------------------------------------------------}}
            @endforeach
            </tbody>
        </table>
    </div>
</div>





{{-----------------------------------------------------------------------------------------------------}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



{{-----------------------------------------------------------------------------------------------------}}
</body>
</html>
@endsection

