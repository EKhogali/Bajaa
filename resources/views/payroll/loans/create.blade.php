@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>إضافة قرض جديد</h3>
        <form action="{{ route('loan_headers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="employee_id" class="form-label">الموظف</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">اختر الموظف</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="months" class="form-label">عدد الأشهر</label>
                <input type="number" name="months" id="months" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">تاريخ البداية</label>
                <input type="month" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descrpt" class="form-label">الوصف</label>
                <textarea name="descrpt" id="descrpt" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="archived" class="form-label">حالة الأرشيف</label>
                <select
                    class="form-control @error('archived') is-invalid @enderror"
                    id="archived"
                    name="archived">
                    <option value="0" {{ old('archived') == '0' ? 'selected' : '' }}>غير مؤرشف</option>
                    <option value="1" {{ old('archived') == '1' ? 'selected' : '' }}>مؤرشف</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('loan_headers.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
@endsection
