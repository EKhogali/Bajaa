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
                <form action="/sitting/{{$sitting->id}}" method="POST" >
                    {{ csrf_field() }}
                    @method('PUT')

                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">حساب فائض الخزينة</label>
                        <select class="form-select" name="Cashbox_Faaed_Account">
                            <option selected disabled>اختر الحساب</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ $account->id == $sitting->Cashbox_Faaed_Account ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">حساب عجز الخزينة</label>
                        <select class="form-select" name="Cashbox_Ajz_Account">
                            <option selected disabled>اختر الحساب</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ $account->id == $sitting->Cashbox_Ajz_Account ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <br>
                    <div class="container-fluid row">
                        <div class="col-6">
                            <label class="form-label">مجموعة ايرادات اخرى</label>
                            <select class="form-select" name="Other_Incom">
                                <option selected disabled>اختر المجموعة</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $sitting->Other_Incom ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">مجموعة المصروفات التشغيلية</label>
                        <select class="form-select" name="Operation_Account">
                            <option selected disabled>اختر المجموعة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $sitting->operation_accounts_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">مجموعة المصروفات الادارية</label>
                        <select class="form-select" name="Administrative_Account">
                            <option selected disabled>اختر المجموعة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $sitting->administrative_accounts_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">مجموعة حسابات الديون</label>
                        <select class="form-select" name="dioon_account_category">
                            <option selected disabled>اختر المجموعة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $sitting->dioon_account_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">مجموعة حسابات مسحوبات من صافي الدخل</label>
                        <select class="form-select" name="dioon_account_category">
                            <option selected disabled>اختر المجموعة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $sitting->pulled_from_net_income_accounts_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="container-fluid row">
                    <div class="col-6">
                        <label class="form-label">عدد الخانات العشرية</label>
                        <select class="form-select" name="decimal_octets">
                            <option selected disabled>اختر عدد الخانات</option>
                            <option value="1" @if($sitting->decimal_octets == 1) selected @endif > 1 </option>
                            <option value="2" @if($sitting->decimal_octets == 2) selected @endif > 2 </option>
                            <option value="3" @if($sitting->decimal_octets == 3) selected @endif > 3 </option>
                            <option value="4" @if($sitting->decimal_octets == 4) selected @endif > 4 </option>
                            <option value="5" @if($sitting->decimal_octets == 5) selected @endif > 5 </option>
                        </select>
                    </div>
                </div>
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
