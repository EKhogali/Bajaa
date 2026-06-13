@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">تسجيل حركة مالية</h1>
            </div>
        </div>
    </div>
</div>

<div class="content text-right" dir="rtl">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card card-success card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-right w-100 font-weight-bold" style="float: right;">
                            <i class="fas fa-plus ml-2 text-success"></i> قيد حركة مالية جديدة لمورد
                        </h3>
                    </div>

                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            
                            <div class="form-group row mb-3">
                                <label for="vendor_id" class="col-sm-3 col-form-label font-weight-bold">المورد المستهدف <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                                        <option value="">-- اختر المورد من القائمة --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }} (الرصيد الحالي: {{ number_format($vendor->balance, 2) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="date" class="col-sm-3 col-form-label font-weight-bold">تاريخ القيد <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control text-right" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">نوع الحركة المادية <span class="text-danger">*</span></label>
                                <div class="col-sm-9 d-flex align-items-center" style="gap: 20px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_debit" value="debit" checked required>
                                        <label class="form-check-label mr-4 text-success font-weight-bold" for="type_debit">مدين (+) (له / فاتورة شراء أو إستحقاق)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_credit" value="credit" required>
                                        <label class="form-check-label mr-4 text-danger font-weight-bold" for="type_credit">دائن (-) (عليه / سداد نقدي أو دفعة مادية)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="amount" class="col-sm-3 col-form-label font-weight-bold">المبلغ المالي <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" min="0.01" class="form-control text-left" id="amount" name="amount" placeholder="0.00" required dir="ltr">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="description" class="col-sm-3 col-form-label font-weight-bold">البيان / الشرح</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="اكتب تفاصيل أو سبب الحركة المالية بالتفصيل..."></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tags" class="col-sm-3 col-form-label font-weight-bold">وسوم الحركة</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tags" name="tags" placeholder="مثال: نقدي, شيك, توريد_مواد">
                                    <small class="form-text text-muted mt-1">افصل بين الوسوم بفاصلة ( , ).</small>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-success font-weight-bold ml-2">
                                <i class="fas fa-save ml-1"></i> حفظ القيد المالي
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-default">إلغاء الأمر</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection