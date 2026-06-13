@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">تعديل بيانات المورد</h1>
            </div>
        </div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card card-warning card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-right w-100 font-weight-bold" style="float: right;">
                            <i class="fas fa-edit ml-2 text-warning"></i> تعديل حساب: {{ $vendor->name }}
                        </h3>
                    </div>

                    <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            
                            <div class="form-group row mb-3">
                                <label for="name" class="col-sm-3 col-form-label font-weight-bold">إسم المورد <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tel" class="col-sm-3 col-form-label font-weight-bold">رقم الهاتف</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control text-left" id="tel" name="tel" value="{{ old('tel', $vendor->tel) }}" dir="ltr">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tags" class="col-sm-3 col-form-label font-weight-bold">الوسوم / التصنيفات</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $vendor->tags->pluck('name')->implode(', ')) }}" placeholder="VIP, محلي, جملة">
                                    <small class="form-text text-muted mt-1">افصل بين الوسوم بفاصلة عادية ( , ).</small>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-warning font-weight-bold ml-2">
                                <i class="fas fa-sync ml-1"></i> تحديث البيانات
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