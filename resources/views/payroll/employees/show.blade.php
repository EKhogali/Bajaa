@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>عرض بيانات الموظف</h3>
        <br>
        @if(session()->has('message'))
            @if(session()->has('msgtype'))
                <div class="alert alert-{{ session()->get('msgtype') == 'success' ? 'success' : 'danger' }}">
                    {{ session()->get('message') }}
                </div>
            @endif
        @endif

        <br>
        <div class="row">
            <div class="col-md-4">
                <h5>بيانات الموظف</h5>
                <p class="bg-light p-2"><strong>الاسم:</strong> {{ $employee->name }}</p>
                <p class="bg-light p-2"><strong>الرمز:</strong> {{ $employee->code }}</p>
                <p class="bg-light p-2"><strong>الراتب الأساسي:</strong> {{ number_format($employee->basic_salary, 2) }}</p>
                <p class="bg-light p-2"><strong>القسم:</strong> {{ $employee->department->name }}</p>

                {{--                <p><strong>الأرشيف:</strong> {{ $employee->archived ? 'نعم' : 'لا' }}</p>--}}
            </div>
            <div class="col-md-6">
                <h5>بنود الراتب</h5>
                <br>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPayrollItemModal">إضافة بند</button>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="25%">النوع</th>
                        <th width="10%">المبلغ</th>
                        <th>الوصف</th>
                        <th width="10%">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employee->constantPayrollItems as $item)
                        <tr>
                            <td>{{ $item->payrollitem->name }}</td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                            <td>{{$item->descrpt}}</td>
                            <td>
{{--                                <button class="btn btn-warning btn-sm edit-item-btn"--}}
{{--                                        data-bs-toggle="modal"--}}
{{--                                        data-bs-target="#editPayrollItemModal"--}}
{{--                                        data-id="{{ $item->id }}"--}}
{{--                                        data-descrpt="{{ $item->descrpt }}"--}}
{{--                                        data-amount="{{ $item->amount }}"--}}
{{--                                        data-type="{{ $item->type }}">--}}
{{--                                    تعديل--}}
{{--                                </button>--}}
                                <form action="{{ route('employee_constant_payroll_items.destroy', $item->id) }}" method="POST" class="d-inline">
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
    </div>

    <!-- Modal for Adding Payroll Item -->
    <div class="modal fade" id="addPayrollItemModal" tabindex="-1" aria-labelledby="addPayrollItemLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employee_constant_payroll_items.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPayrollItemLabel">إضافة بند راتب</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="col-md-6">
                                <label for="payroll_item_type_id" class="form-label">بند الراتب</label>
                                <select class="form-select" id="payroll_item_type_id" name="payroll_item_type_id" required>
                                    @foreach($payroll_item_types as $payroll_item_type)
                                        <option value="{{ $payroll_item_type->id }}">{{ $payroll_item_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descrpt" class="form-label">الوصف</label>
                            <input type="text" class="form-control" id="descrpt" name="descrpt" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">المبلغ</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">النوع</label>
                            <select class="form-select" id="type" name="type">
                                <option value="0" selected>زيادة</option>
                                <option value="1">خصم</option>
                            </select>
                        </div>
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPayrollItemModal" tabindex="-1" aria-labelledby="editPayrollItemLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPayrollItemForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPayrollItemLabel">تعديل بند الراتب</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-descrpt" class="form-label">الوصف</label>
                            <input type="text" class="form-control" id="edit-descrpt" name="descrpt" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-amount" class="form-label">المبلغ</label>
                            <input type="number" class="form-control" id="edit-amount" name="amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-type" class="form-label">النوع</label>
                            <select class="form-select" id="edit-type" name="type">
                                <option value="0">زيادة</option>
                                <option value="1">خصم</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">تعديل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editButtons = document.querySelectorAll('.edit-item-btn');
            const editForm = document.getElementById('editPayrollItemForm');

            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const descrpt = button.getAttribute('data-descrpt');
                    const amount = button.getAttribute('data-amount');
                    const type = button.getAttribute('data-type');

                    // Populate the modal fields
                    document.getElementById('edit-descrpt').value = descrpt;
                    document.getElementById('edit-amount').value = amount;
                    document.getElementById('edit-type').value = type;

                    // Update the form's action URL
                    editForm.action = `/employee_constant_payroll_items/${id}`;
                });
            });
        });

    </script>
@endsection
