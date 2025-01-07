@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>تعديل بيانات الموظف</h3>

        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <br><br>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">اسم الموظف</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="code" class="form-label">الرمز</label>
                    <input type="text" class="form-control" id="code" name="code" value="{{ $employee->code }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="job_id" class="form-label">الوظيفة</label>
                    <select class="form-select" id="job_id" name="job_id" required>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ $employee->job_id == $job->id ? 'selected' : '' }}>
                                {{ $job->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="department_id" class="form-label">القسم</label>
                    <select class="form-select" id="department_id" name="department_id" required>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $employee->department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="basic_salary" class="form-label">الراتب الأساسي</label>
                    <input type="number" class="form-control" id="basic_salary" name="basic_salary" step="0.01" value="{{ $employee->basic_salary }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="hire_date" class="form-label">تاريخ التوظيف</label>
                    <input type="date" class="form-control" id="hire_date" name="hire_date" value="{{ $employee->hire_date }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="gender" class="form-label">الجنس</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="0" {{ $employee->gender == 0 ? 'selected' : '' }}>ذكر</option>
                        <option value="1" {{ $employee->gender == 1 ? 'selected' : '' }}>انثى</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="dob" class="form-label">تاريخ الميلاد</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ $employee->dob }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="marital_state_id" class="form-label">الحالة الاجتماعية</label>
                    <select class="form-select" id="marital_state_id" name="marital_state_id">
                        <option value="0" {{ $employee->marital_state_id == 0 ? 'selected' : '' }}>عازب/ة</option>
                        <option value="1" {{ $employee->marital_state_id == 1 ? 'selected' : '' }}>متزوج/ة</option>
                        <option value="2" {{ $employee->marital_state_id == 2 ? 'selected' : '' }}>مطلق/ة</option>
                        <option value="3" {{ $employee->marital_state_id == 3 ? 'selected' : '' }}>ارمل/ة</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="archived" class="form-label">أرشيف</label>
                    <select class="form-select" id="archived" name="archived">
                        <option value="0" {{ $employee->archived == 0 ? 'selected' : '' }}>لا</option>
                        <option value="1" {{ $employee->archived == 1 ? 'selected' : '' }}>نعم</option>
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
