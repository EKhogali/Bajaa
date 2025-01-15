@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>تعديل بيانات القرض</h3>
        <form action="{{ route('loan_headers.update', $loan_header->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="employee_id" class="form-label">الموظف</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">اختر الموظف</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $loan_header>employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $loan_header->amount }}" required>
            </div>
            <div class="mb-3">
                <label for="months" class="form-label">عدد الأشهر</label>
                <input type="number" name="months" id="months" class="form-control" value="{{ $loan_header->months }}" required>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">تاريخ البداية</label>
                <input type="month" name="start_date" id="start_date" class="form-control" value="{{ sprintf('%04d-%02d', $loan_header->start_year, $loan_header->start_month) }}" required>
            </div>
            <div class="mb-3">
                <label for="descrpt" class="form-label">الوصف</label>
                <textarea name="descrpt" id="descrpt" class="form-control">{{ $loan_header->descrpt }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">تحديث</button>
            <a href="{{ route('loan_headers.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
@endsection
