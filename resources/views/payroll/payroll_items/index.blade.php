@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>أنواع بنود الرواتب</h3>

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
            <h4>انواع مفردات الراتب</h4>
            <a href="{{ route('payroll_item_type.create') }}" class="btn btn-primary">إضافة نوع جديد</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="5%">الرقم</th>
                    <th>الاسم</th>
                    <th width="10%">النوع</th>
                    <th width="10%">أرشيف</th>
                    <th width="10%">إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payrollItemTypes as $itemType)
                    <tr>
                        <td width="5%">{{ $itemType->id }}</td>
                        <td>{{ $itemType->name }}</td>
                        <td width="10%">
                            @if($itemType->type == 0)
                                <span class="badge bg-success">زيادة</span>
                            @else
                                <span class="badge bg-danger">خصم</span>
                            @endif
                        </td>
                        <td width="10%">
                            @if($itemType->archived)
                                <span class="badge bg-danger">مؤرشف</span>
                            @else
                                <span class="badge bg-success">لا</span>
                            @endif
                        </td>
                        <td width="10%">
                            <a href="{{ route('payroll_item_type.edit', $itemType->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('payroll_item_type.destroy', $itemType->id) }}" method="POST" class="d-inline">
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
