@extends('layout.master')

@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3>تعديل الشريك</h3>
</div>
@if(session()->has('message'))
    @if(session()->has('msgtype'))
        @if(session()->get('msgtype') == 'success')
            <div class="alert alert-success">
                @elseif(session()->get('msgtype') == 'notsuccess')
                    <div class="alert alert-danger">
                        @endif
                        @endif
                        {{ session()->get('message') }}
                    </div>
                @endif
                <br>
                <div class="container row">
                    <div class="container col-2">

                    </div>
                    <div class="container col-10">
                        <form action="{{ route('partners.update', $partner->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid row">
                                <div class="col-6">
                                    <label for="name" class="form-label">اسم الشريك</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $partner->name) }}">
                                </div>
                            </div>
                            <br>
                            <div class="container-fluid row">
                                <div class="col-6">
                                    <label for="partnership_type" class="form-label">نوع الشراكة</label>
                                    <select class="form-control" id="partnership_type" name="partnership_type">
                                        <option value="0" {{ old('partnership_type', $partner->partnership_type) == 0 ? 'selected' : '' }}>مستثمر</option>
                                        <option value="1" {{ old('partnership_type', $partner->partnership_type) == 1 ? 'selected' : '' }}>شريك</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="container-fluid row">
                                <div class="col-6">
                                    <label for="account_id" class="form-label">الحساب</label>
                                    <select class="form-control" id="account_id" name="account_id" required>
                                        <option value="">اختر الحساب</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('account_id', $partner->account_id) == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="container-fluid row">
                                <div class="col-6">
                                    <label for="win_percentage" class="form-label">النسبة</label>
                                    <input type="text" class="form-control" id="win_percentage" name="win_percentage" min="0" max="100" value="{{ old('win_percentage', $partner->win_percentage) }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                                <div class="col"></div>
                            </div>
                        </form>
                    </div>
                </div>

</body>
</html>
@endsection
