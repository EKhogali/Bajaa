@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>

<br>
<div class="container">
    <h3>إضافة قسم جديد</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">

    </div>
    <div class="container col-10">
        <form action="{{ route('departments.store') }}" method="POST">
            {{ csrf_field() }}
            <div class="container-fluid row">
                <!-- Department Name Input -->
                <div class="col-6">
                    <label for="name" class="form-label">اسم القسم</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="أدخل اسم القسم">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <br>
            <div class="container-fluid row">
                <!-- Archive Status Dropdown -->
                <div class="col-6">
                    <label for="archived" class="form-label">حالة الأرشيف</label>
                    <select
                        class="form-control @error('archived') is-invalid @enderror"
                        id="archived"
                        name="archived">
                        <option value="0" {{ old('archived') == '0' ? 'selected' : '' }}>غير مؤرشف</option>
                        <option value="1" {{ old('archived') == '1' ? 'selected' : '' }}>مؤرشف</option>
                    </select>
                    @error('archived')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-3"></div>
                <div class="col-3">
                    <!-- Save Button -->
                    <button type="submit" class="btn btn-primary">حفــظ</button>
                </div>
                <div class="col-3">
                    <!-- Cancel Button -->
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
                <div class="col"></div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
@endsection
