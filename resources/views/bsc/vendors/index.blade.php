@extends('layout.master')

@section('content')
    <div class="content-header text-right">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">دليل الموردين</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content text-right" dir="rtl">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show text-right" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="close float-left" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title text-right font-weight-bold m-0" style="flex: 1;">
                                <i class="fas fa-boxes ml-2 text-primary"></i> قائمة الموردين المسجلين
                            </h3>

                            <div class="card-tools d-flex gap-2" style="gap: 10px;">
                                <a href="{{ route('vendors.create') }}" class="btn btn-success font-weight-bold shadow-sm">
                                    <i class="fas fa-plus ml-1"></i> إضافة مورد جديد
                                </a>
                                <form action="{{ route('vendors.recalculate') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary font-weight-bold shadow-sm">
                                        <i class="fas fa-sync ml-1"></i> تحديث الأرصدة
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-striped text-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 70px;">#</th>
                                        <th>اسم المورد</th>
                                        <th>رقم الهاتف</th>
                                        <th>الرصيد الحالي</th>
                                        <th>التصنيفات والوسوم</th>
                                        <th style="width: 180px;">العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendors as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="font-weight-bold text-primary">{{ $vendor->name }}</td>
                                            <td>{{ $vendor->tel ?? '---' }}</td>
                                            <td
                                                class="font-weight-bold {{ $vendor->balance < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($vendor->balance, 2) }}
                                            </td>
                                            <td>
                                                @forelse($vendor->tags as $tag)
                                                    <span class="badge badge-warning px-2 py-1 ml-1 text-dark font-weight-bold">
                                                        {{ $tag->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted small">لا توجد وسوم</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <!-- <a href="#" class="btn btn-sm btn-info ml-1" title="عرض الحركات">
                                                        <i class="fas fa-eye ml-1"></i> عرض
                                                    </a> -->
                                                <a href="{{ route('vendors.edit', $vendor->id) }}"
                                                    class="btn btn-sm btn-warning ml-1" title="تعديل">
                                                    <i class="fas fa-edit ml-1"></i> تعديل
                                                </a>

                                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST"
                                                    class="d-inline-block"
                                                    onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المورد نهائياً؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                        <i class="fas fa-trash ml-1"></i> حذف
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-folder-open d-block mb-2 fa-2x text-secondary"></i>
                                                لا يوجد أي موردين مسجلين حالياً.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection