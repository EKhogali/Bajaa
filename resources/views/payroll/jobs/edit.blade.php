@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>تعديل وظيفة</h3>

        <!-- Session Message -->
        @if(session()->has('message'))
            @if(session()->has('msgtype'))
                <div class="alert alert-{{ session()->get('msgtype') == 'success' ? 'success' : 'danger' }}">
                    {{ session()->get('message') }}
                </div>
            @endif
        @endif

        <br><br>

        <form action="{{ route('jobs.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">اسم الوظيفة</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $job->name }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="archived" class="form-label">أرشيف</label>
                    <select class="form-select" id="archived" name="archived">
                        <option value="0" {{ !$job->archived ? 'selected' : '' }}>لا</option>
                        <option value="1" {{ $job->archived ? 'selected' : '' }}>نعم</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary">تحديــث</button>
                </div>
            </div>
        </form>
    </div>
@endsection
