@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2"><div class="col-sm-6"><h1 class="m-0 text-dark">تصنيفات الموردين</h1></div></div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">

        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="close float-left" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="close float-left" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold m-0"><i class="fas fa-tags ml-2 text-primary"></i> قائمة تصنيفات الموردين</h3>
                <a href="{{ route('vendor_groups.create') }}" class="btn btn-success font-weight-bold">
                    <i class="fas fa-plus ml-1"></i> إضافة تصنيف جديد
                </a>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped text-center mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:70px;">#</th>
                            <th>اسم التصنيف</th>
                            <th>عدد الموردين</th>
                            <th style="width:180px;">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold text-primary">{{ $group->name }}</td>
                                <td>{{ $group->vendors_count }}</td>
                                <td>
                                    <a href="{{ route('vendor_groups.edit', $group->id) }}" class="btn btn-sm btn-warning ml-1">
                                        <i class="fas fa-edit ml-1"></i> تعديل
                                    </a>
                                    <form action="{{ route('vendor_groups.destroy', $group->id) }}" method="POST" class="d-inline-block"
                                        onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا التصنيف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash ml-1"></i> حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">لا توجد تصنيفات مسجلة حالياً.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection