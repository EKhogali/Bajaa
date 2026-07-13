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
                                    <label for="name" class="col-sm-3 col-form-label font-weight-bold">إسم المورد <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="tel" class="col-sm-3 col-form-label font-weight-bold">رقم الهاتف</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control text-left" id="tel" name="tel">
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="vendor_group_id" class="col-sm-3 col-form-label font-weight-bold">تصنيف
                                        المورد</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="vendor_group_id" id="vendor_group_id">
                                            <option value="">-- بدون تصنيف --</option>
                                            @foreach($groups as $g)
                                                <option value="{{ $g->id }}" {{ old('vendor_group_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label font-weight-bold">نطاق المورد</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="vendor_scope" id="scope_specific" value="specific"
                                                {{ old('vendor_scope', 'specific') == 'specific' ? 'checked' : '' }} onchange="toggleCompanyScope()">
                                            <label class="form-check-label" for="scope_specific">شركة أو شركات محددة</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="vendor_scope" id="scope_all" value="all"
                                                {{ old('vendor_scope') == 'all' ? 'checked' : '' }} onchange="toggleCompanyScope()">
                                            <label class="form-check-label" for="scope_all">جميع الشركات</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-3" id="companyIdsWrap">
                                    <label class="col-sm-3 col-form-label font-weight-bold">الشركات المرتبطة</label>
                                    <div class="col-sm-9">
                                        @php $selectedCompanyIds = old('company_ids', [session('company_id')]); @endphp
                                        @foreach($companies as $c)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="company_ids[]" value="{{ $c->id }}"
                                                    id="company_{{ $c->id }}" {{ in_array($c->id, $selectedCompanyIds) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="company_{{ $c->id }}">{{ $c->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="tags" class="col-sm-3 col-form-label font-weight-bold">الوسوم /
                                        التصنيفات</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tags" name="tags">
                                        <small class="form-text text-muted mt-1">اكتب الأسماء مفصولة بفاصلة عادية ( ,
                                            ).</small>
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

    <script>
        function toggleCompanyScope() {
            var isAll = document.getElementById('scope_all').checked;
            document.getElementById('companyIdsWrap').style.display = isAll ? 'none' : '';
        }
        document.addEventListener('DOMContentLoaded', toggleCompanyScope);
    </script>
@endsection