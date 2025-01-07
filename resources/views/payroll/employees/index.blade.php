@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>الموظفون</h3>

        <!-- Session Message -->
        @if(session()->has('message'))
            @if(session()->has('msgtype'))
                <div class="alert alert-{{ session()->get('msgtype') == 'success' ? 'success' : 'danger' }}">
                    {{ session()->get('message') }}
                </div>
            @endif
        @endif

        <br>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>قائمة الموظفين</h4>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">إضافة موظف جديد</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>الرقم</th>
                    <th>الاسم</th>
                    <th>الرمز</th>
                    <th>الراتب الأساسي</th>
                    <th>تاريخ التوظيف</th>
                    <th>الوظيفة</th>
                    <th>القسم</th>
                    <th>الأرشيف</th>
                    <th>إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td width="5%">{{ $employee->id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->code }}</td>
                        <td>{{ number_format($employee->basic_salary, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->hire_date)->format('d-m-yy') }}</td>
                        <td>{{ $employee->job->name }}</td>
                        <td>{{ $employee->department->name }}</td>
                        <td width="10%">
                            @if($employee->archived)
                                <span class="badge bg-danger">مؤرشف</span>
                            @else
                                <span class="badge bg-success">لا</span>
                            @endif
                        </td>
                        <td width="15%">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">عرض</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
