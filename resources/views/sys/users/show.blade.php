@extends('layout.master')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">بيانات المستخدم: {{ $user->name }}</h3>
        <a href="{{ url('/users') }}" class="btn btn-secondary">رجوع</a>
    </div>

    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mt-4">

        {{-- User Info --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-bold">معلومات المستخدم</div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th>رقم المستخدم</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>اسم المستخدم</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>البريد الالكتروني</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>الصفة</th>
                                <td>
                                    @switch($user->type)
                                        @case(1) مدير النظام @break
                                        @case(2) مشرف النظام @break
                                        @case(3) مدخل بيانات @break
                                        @case(4) مسؤول تقارير @break
                                        @default -
                                    @endswitch
                                </td>
                            </tr>

                            <tr>
                                <th>الحساب مؤرشف</th>
                                <td>
                                    <span class="badge {{ $user->archived ? 'bg-danger' : 'bg-success' }}">
                                        {{ $user->archived ? 'نعم' : 'لا' }}
                                    </span>
                                </td>
                            </tr>

                            {{-- Current selections (IDs only unless you pass relations) --}}
                            <tr>
                                <th>الشركة الحالية</th>
                                <td>{{ $user->current_company_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>الخزينة الحالية</th>
                                <td>{{ $user->current_treasury_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>السنة المالية الحالية</th>
                                <td>{{ $user->current_financial_year_id ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Actions (Admin/Supervisor only) --}}
            @if(auth()->user()->type == 1 || auth()->user()->type == 2)
                <div class="d-grid gap-2 mb-2">
                    <a class="btn btn-warning" href="{{ url('/users/'.$user->id.'/edit') }}">
                        تعديل بيانات المستخدم
                    </a>
                </div>

                @if($user->type != 1)
                    <form method="POST"
                          action="{{ action('UserController@destroy', $user->id) }}"
                          onsubmit="return confirm('هل أنت متأكد من حذف المستخدم؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            حذف المستخدم
                        </button>
                    </form>
                @endif
            @endif
        </div>

        {{-- Permissions --}}
        {{-- Permissions --}}
<div class="col-md-8">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">صلاحيات المستخدم</span>

            {{-- Add predefined permission button (Admin/Supervisor only) --}}
            @if(auth()->user()->type == 1 || auth()->user()->type == 2)
                @php
                    $hasAccountDetailsReport = $user_permissions->where('permission_name', 'account_details_report')->count() > 0;
                @endphp

                @if(!$hasAccountDetailsReport)
                    <form action="{{ route('user_permissions.store') }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="permission_name" value="account_details_report">

                        <button type="submit" class="btn btn-primary btn-sm">
                            اضافة صلاحية تقرير الحسابات التفصيلية
                        </button>
                    </form>
                @else
                    <button class="btn btn-success btn-sm" disabled>
                        صلاحية تقرير الحسابات التفصيلية موجودة ✅
                    </button>
                @endif
            @endif
        </div>

        <div class="card-body">

            {{-- Permissions Table --}}
            @if($user_permissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الصلاحية</th>

                                @if(auth()->user()->type == 1 || auth()->user()->type == 2)
                                    <th width="15%">حذف</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($user_permissions as $perm)
                                <tr>
                                    <td>{{ $perm->id }}</td>

                                    {{-- Friendly Arabic name --}}
                                    <td>
                                        @if($perm->permission_name == 'account_details_report')
                                            تقرير الحسابات التفصيلية
                                            <span class="text-muted">({{ $perm->permission_name }})</span>
                                        @else
                                            {{ $perm->permission_name }}
                                        @endif
                                    </td>

                                    @if(auth()->user()->type == 1 || auth()->user()->type == 2)
                                        <td>
                                            <form action="{{ route('user_permissions.destroy', $perm->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    حذف
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    لا توجد صلاحيات لهذا المستخدم.
                </div>
            @endif

        </div>
    </div>
</div>


    </div>
</div>
@endsection
