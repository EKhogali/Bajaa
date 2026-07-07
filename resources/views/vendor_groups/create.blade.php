@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2"><div class="col-sm-6"><h1 class="m-0 text-dark">إضافة تصنيف مورد جديد</h1></div></div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header"><h3 class="card-title font-weight-bold">بيانات التصنيف</h3></div>
                    <form action="{{ route('vendor_groups.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">اسم التصنيف <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-success font-weight-bold ml-2"><i class="fas fa-save ml-1"></i> حفظ</button>
                            <a href="{{ route('vendor_groups.index') }}" class="btn btn-default">إلغاء الأمر</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection