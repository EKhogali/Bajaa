@extends('layout.master')
@section('content')
    <div class="container">
        <h3>تحرير الخزينة</h3>
    </div>
    <br>
    <div class="container row">
        <div class="container col-2"></div>
        <div class="container col-10">
            @if(session()->has('message'))
                <div class="alert alert-{{ session()->get('msgtype', 'info') }}">
                    {{ session()->get('message') }}
                </div>
            @endif
            <form action="{{ route('treasuries.update', ['treasury' => $treasury->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="container-fluid row">
                    <div class="col-6">
                        <label for="name" class="form-label">اسم الخزينة</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $treasury->name }}">
                    </div>
                </div>
                <br>
{{--                <div class="container-fluid row">--}}
{{--                    <div class="col-6">--}}
{{--                        <label for="account_id" class="form-label">الحساب</label>--}}
{{--                        <select class="form-select" id="account_id" name="account_id">--}}
{{--                            <option selected disabled>اختر الحساب</option>--}}
{{--                            @foreach($treasury_accounts as $account)--}}
{{--                                <option value="{{ $account->id }}" {{ $account->id == $treasury->account_id ? 'selected' : '' }}>--}}
{{--                                    {{ $account->name }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <br>
                <div class="row">
                    <div class="col"></div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </div>
                    <div class="col"></div>
                </div>
            </form>
        </div>
    </div>
@endsection
