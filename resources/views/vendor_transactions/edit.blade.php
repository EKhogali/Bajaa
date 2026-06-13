@extends('layout.master')

@section('content')
<div class="content-header text-right">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">تعديل الحركة المالية</h1>
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
                            <i class="fas fa-edit ml-2 text-warning"></i> مراجعة وتعديل بيانات القيد المالي رقم #{{ $transaction->id }}
                        </h3>
                    </div>

                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            
                            <div class="form-group row mb-3">
                                <label for="vendor_id" class="col-sm-3 col-form-label font-weight-bold">اسم المورد <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ $transaction->vendor_id == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="date" class="col-sm-3 col-form-label font-weight-bold">تاريخ القيد <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control text-right" id="date" name="date" value="{{ old('date', $transaction->date) }}" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">نوع القيد المالي <span class="text-danger">*</span></label>
                                <div class="col-sm-9 d-flex align-items-center" style="gap: 20px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_debit" value="debit" {{ $currentType === 'debit' ? 'checked' : '' }} required>
                                        <label class="form-check-label mr-4 text-success font-weight-bold" for="type_debit">مدين (+) (له / شراء أو إستحقاق)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_credit" value="credit" {{ $currentType === 'credit' ? 'checked' : '' }} required>
                                        <label class="form-check-label mr-4 text-danger font-weight-bold" for="type_credit">دائن (-) (عليه / سداد مالي أو دفعة)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="amount" class="col-sm-3 col-form-label font-weight-bold">المبلغ المالي <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" min="0.01" class="form-control text-left" id="amount" name="amount" value="{{ old('amount', $currentAmount) }}" required dir="ltr">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="description" class="col-sm-3 col-form-label font-weight-bold">البيان / الشرح</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $transaction->description) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tags" class="col-sm-3 col-form-label font-weight-bold">وسوم الحركة</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $transaction->tags->pluck('name')->implode(', ')) }}">
                                    <small class="form-text text-muted mt-1">افصل بين الوسوم بفاصلة ( , ).</small>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-warning font-weight-bold ml-2">
                                <i class="fas fa-sync ml-1"></i> تحديث التعديلات
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