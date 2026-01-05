@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3 >New Financial Year</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="/financial_years" method="POST" >
            {{ csrf_field() }}
            <div class="container-fluid row ">
                <div class="col-6">
                    <label for="financial_year" class="form-label">الصلاحية</label>
                    <input type="number" class="form-control" id="financial_year" name="financial_year"></div>
            </div>
            <input type="text" name="company_id" hidden value="{{Request()->company_id}}">
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

