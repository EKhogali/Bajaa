@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>الأقسام</h3>

        <!-- Session Message -->
        @if(session()->has('message'))
            @if(session()->has('msgtype'))
                <div class="alert alert-{{ session()->get('msgtype') == 'success' ? 'success' : 'danger' }}">
                    {{ session()->get('message') }}
                </div>
            @endif
        @endif

        <br><br>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>قائمة الأقسام</h4>
            <a href="{{ route('departments.create') }}" class="btn btn-primary">إضافة قسم</a>
        </div>

        <div class="table-responsive">
            <table class="table ">
                <thead class="table-light">
                <tr>
                    <th>الرقم</th>
                    <th>الاسم</th>
                    <th>أرشيف</th>
                    <th>إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td width="5%">{{ $department->id }}</td>
                        <td>{{ $department->name }}</td>
                        <td>
                            @if($department->archived)
                                <span class="badge bg-danger">نعم</span>
                            @else
                                <span class="badge bg-success">لا</span>
                            @endif
                        </td>
                        <td width="10%">
                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
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
