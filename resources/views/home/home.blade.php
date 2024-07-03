@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="ar">
<style>
    body {
        background-image: url('images/bg02.jpg');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }
</style>
<body>
<h1>المحاسبة الرقمية | Digital Accounting</h1>

<div class="row">
    <div class="column col-5">
        <br><br>
        <div class="card">
            <div class="card-header">
                <h4>الشركات -> السنوات المالية</h4>
            </div>


            <div class="card-body">
                {{-- List of companies --}}
                <ul class="list-group">
                    @foreach($companies as $company)
                        <li class="list-group-item">
                            <h2>{{$company->name}}</h2>
                            <ul class="list-group">
                                @forelse($financial_years->where('company_id', $company->id) as $financial_year)
                                    <li class="list-group-item">
                                        <a href="/company_and_financial_year?financial_year_id={{$financial_year->id}}&company_id={{$company->id}}">
                                            {{$financial_year->financial_year}}
                                        </a>
                                    </li>
                                @empty
                                    <li class="list-group-item">No financial years found for this company.</li>
                                @endforelse
                            </ul>
                        </li>
                    @endforeach
                </ul>
                {{-- End list of companies --}}
            </div>

        </div>
    </div>
    <div class="column col-7">
        {{-- Additional content column --}}
    </div>
</div>

</body>
</html>
@endsection
