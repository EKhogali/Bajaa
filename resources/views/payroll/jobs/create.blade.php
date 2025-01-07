@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>إضافة وظيفة جديدة</h3>

        <!-- Session Message -->
        @if(session()->has('message'))
            @if(session()->has('msgtype'))
                <div class="alert alert-{{ session()->get('msgtype') == 'success' ? 'success' : 'danger' }}">
                    {{ session()->get('message') }}
                </div>
            @endif
        @endif

        <br><br>

        <form action="{{ route('jobs.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">اسم الوظيفة</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="archived" class="form-label">أرشيف</label>
                    <select class="form-select" id="archived" name="archived">
                        <option value="0" selected>لا</option>
                        <option value="1">نعم</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary">حفــظ</button>
                </div>
            </div>
        </form>
    </div>
@endsection
