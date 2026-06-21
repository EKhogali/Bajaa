@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إضافة مورد جديد</h1>
            </div>
        </div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-right w-100 font-weight-bold" style="float: right;">
                            <i class="fas fa-plus ml-2 text-primary"></i> بيانات المورد الجديد
                        </h3>
                    </div>

                    <form action="{{ route('vendors.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            
                            <div class="form-group row mb-3">
                                <label for="name" class="col-sm-3 col-form-label font-weight-bold">إسم المورد <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="name" required >
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tel" class="col-sm-3 col-form-label font-weight-bold">رقم الهاتف</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control text-left" id="tel" name="tel" >
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tags" class="col-sm-3 col-form-label font-weight-bold">الوسوم / التصنيفات</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tags" name="tags" >
                                    <small class="form-text text-muted mt-1">اكتب الأسماء مفصولة بفاصلة عادية ( , ).</small>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-success font-weight-bold ml-2">
                                <i class="fas fa-save ml-1"></i> حفظ المورد
                            </button>
                            <a href="{{ route('vendors.index') }}" class="btn btn-default">إلغاء الأمر</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection