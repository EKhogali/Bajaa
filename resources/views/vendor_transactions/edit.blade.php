@extends('layout.master')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: .25rem;
        min-height: 38px;
        padding: 3px 6px;
        background-color: #fff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border: none;
        border-radius: 20px;
        padding: 2px 12px;
        font-size: 12px;
        font-weight: bold;
        margin: 2px 3px;
        color: #fff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        margin-left: 6px;
        font-weight: bold;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }
    .select2-container { width: 100% !important; }

    .select2-selection__choice:nth-child(8n+1) { background-color: #3498db; }
    .select2-selection__choice:nth-child(8n+2) { background-color: #2ecc71; }
    .select2-selection__choice:nth-child(8n+3) { background-color: #e67e22; }
    .select2-selection__choice:nth-child(8n+4) { background-color: #9b59b6; }
    .select2-selection__choice:nth-child(8n+5) { background-color: #e74c3c; }
    .select2-selection__choice:nth-child(8n+6) { background-color: #1abc9c; }
    .select2-selection__choice:nth-child(8n+7) { background-color: #e91e8c; }
    .select2-selection__choice:nth-child(8n+8) { background-color: #f39c12; }
</style>

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
                            <i class="fas fa-edit ml-2 text-warning"></i>
                            مراجعة وتعديل بيانات القيد المالي رقم #{{ $transaction->id }}
                        </h3>
                    </div>

                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- Vendor --}}
                            <div class="form-group row mb-3">
                                <label for="vendor_id" class="col-sm-3 col-form-label font-weight-bold">
                                    اسم المورد <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                {{ $transaction->vendor_id == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="form-group row mb-3">
                                <label for="date" class="col-sm-3 col-form-label font-weight-bold">
                                    تاريخ القيد <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control text-right" id="date" name="date"
                                        value="{{ old('date', $transaction->date) }}" required>
                                </div>
                            </div>

                            {{-- Type --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    نوع القيد المالي <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9 d-flex align-items-center" style="gap: 20px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type"
                                            id="type_debit" value="debit"
                                            {{ $currentType === 'debit' ? 'checked' : '' }} required>
                                        <label class="form-check-label mr-4 text-success font-weight-bold" for="type_debit">
                                            مدين (+) &mdash; له / شراء أو استحقاق
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type"
                                            id="type_credit" value="credit"
                                            {{ $currentType === 'credit' ? 'checked' : '' }} required>
                                        <label class="form-check-label mr-4 text-danger font-weight-bold" for="type_credit">
                                            دائن (-) &mdash; عليه / سداد مالي أو دفعة
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div class="form-group row mb-3">
                                <label for="amount" class="col-sm-3 col-form-label font-weight-bold">
                                    المبلغ المالي <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" min="0.01" class="form-control text-left"
                                        id="amount" name="amount"
                                        value="{{ old('amount', $currentAmount) }}" required dir="ltr">
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="form-group row mb-3">
                                <label for="description" class="col-sm-3 col-form-label font-weight-bold">
                                    البيان / الشرح
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="description" name="description"
                                        rows="3">{{ old('description', $transaction->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Transaction Tags --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">وسوم الحركة</label>
                                <div class="col-sm-9">
                                    <select class="select2-transaction-tags" name="tags[]" multiple>
                                        @isset($existingTags)
                                            @foreach($existingTags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    {{ $transaction->tags->contains('id', $tag->id) ? 'selected' : '' }}>
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <small class="form-text text-muted mt-1">
                                        اختر وسوماً من القائمة أو اكتب اسم وسم جديد.
                                    </small>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2-transaction-tags').select2({
            dir: 'rtl',
            placeholder: '-- اختر أو أنشئ وسماً --',
            allowClear: true,
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);
                if (!term) return null;
                return { id: 'new:' + term, text: term + ' ✦ جديد' };
            },
        });
    });
</script>

@endsection