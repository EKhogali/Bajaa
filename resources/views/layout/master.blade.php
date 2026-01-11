<!doctype html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>المحاسبة الرقمية | Digital Accounting</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    {{-- Select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

    {{-- jQuery (Select2 requirement) --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
</head>

<body>

    @php
        use App\user_permission;

        $isAdminOrSupervisor = auth()->check() && (auth()->user()->type == 1 || auth()->user()->type == 2);

        $hasAccountDetailsReportPermission = auth()->check() &&
            user_permission::where('user_id', auth()->id())
                ->where('permission_name', 'account_details_report')
                ->exists();
    @endphp



    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                {{-- Menu --}}
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link active" href="/">الرئيسية</a>
                    </li>

                    {{-- Only show menu when user is logged-in and has selected company --}}
                    @if(Auth::check() && session()->has('company_id'))

                            {{-- البيانات الأساسية (Admin/Supervisor only) --}}
                            @if($isAdminOrSupervisor)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        البيانات الأساسية
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/categories">مجموعات الحسابات</a></li>
                                        <li><a class="dropdown-item" href="/classifications">تصنيفات الحسابات</a></li>
                                        <li><a class="dropdown-item" href="/accounts?acc_type=0">دليل الحسابات</a></li>
                                        <li><a class="dropdown-item" href="/accounts?acc_type=1">دليل الاصول الثابتة</a></li>
                                        <li><a class="dropdown-item" href="/accounts?acc_type=2">دليل الحسابات التفصيلية</a></li>
                                        <li><a class="dropdown-item" href="/companies">الشركات / المؤسسات</a></li>
                                        <li><a class="dropdown-item" href="/treasuries">الخزائن</a></li>
                                        <li><a class="dropdown-item" href="/partners">الشركاء</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- الحركة --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    الحركـــــة
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/treasury_transaction?trans_type=0">ايصالات القبض</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/treasury_transaction?trans_type=1">ايصالات الصرف</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="/estimated_expense?transaction_type_id=1">المصروفات
                                            الإدارية التقديرية</a></li>
                                </ul>
                            </li>

                            {{-- المرتبات (Admin/Supervisor only) --}}
                            @if($isAdminOrSupervisor)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        المرتبات
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/jobs">الوظائف</a></li>
                                        <li><a class="dropdown-item" href="/departments">الوحدات الادارية</a></li>
                                        <li><a class="dropdown-item" href="/payroll_item_type">مفردات الراتب</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/employees">بيانات الموظفون</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/loan_headers">السُّلــــــف</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/payroll_transaction">الحركة الشهرية</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/payroll.generate">مرتبات الشهر</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- التقارير --}}
                            @if($isAdminOrSupervisor)
                            <!-- @if($isAdminOrSupervisor || $hasAccountDetailsReportPermission) -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        التقاريــر
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/income_report">تقرير الدخل</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/income_report2">تقرير الدخل2</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/estimated_expense_report">تقرير الدخل التقديري</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="/treasury_report">تقرير الخزينة</a></li>
                                        <li><a class="dropdown-item" href="/ledger2">تقرير حركة حساب</a></li>

                                        <li>
                                            <a class="dropdown-item" href="/account_details_report">
                                                تقرير الحركة التفصيلية لحساب
                                            </a>
                                        </li>

                                        <li><a class="dropdown-item" href="/category_percentage_report">تقرير نسبة المصروفات من
                                                اجمالي المبيعات</a></li>
                                        <li><a class="dropdown-item" href="/pulled_from_net_income_report">تقرير مسحوبات من صافي
                                                الربح</a></li>
                                        <li><a class="dropdown-item" href="/partners_accounts_report">تقرير حساب المستثمر
                                                والشريك</a></li>
                                        <li><a class="dropdown-item" href="/partners_accounts_with_income_report">تقرير مسحوبات من
                                                صافي الربح وحصص المستثمر والشريك</a></li>
                                        <li><a class="dropdown-item" href="/daily_report">التقرير اليومي</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- التقارير --}}
                            @if($isAdminOrSupervisor || $hasAccountDetailsReportPermission)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            تقرير الحسابات التفصيلية
                                        </a>
                                        <ul class="dropdown-menu">


                                            {{-- ✅ Only show this report if Admin/Supervisor OR has permission --}}
                                            @if($isAdminOrSupervisor || $hasAccountDetailsReportPermission)
                                                        <li>
                                                            <a class="dropdown-item" href="/account_details_report">
                                                                تقرير الحركة التفصيلية لحساب
                                                            </a>
                                                        <li><a class="dropdown-item" href="/ledger2">تقرير حركة حساب</a></li>
                                                        <li><a class="dropdown-item" href="/daily_report">التقرير اليومي</a></li>
                                                        <li><a class="dropdown-item" href="/daily_report002">التقرير اليومي 2</a></li>
                                                </li>
                                            @endif

                                </ul>
                                </li>
                            @endif

                        {{-- إدارة النظام (Admin/Supervisor only) --}}
                        @if($isAdminOrSupervisor)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    إدارة النظام
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/sitting">إعدادات النظام</a></li>
                                    <li><a class="dropdown-item" href="/users">المستخدمين والصلاحيات</a></li>
                                </ul>
                            </li>
                        @endif

                    @endif
                </ul>

                {{-- User info (right side) --}}
                @if(Auth::check())
                    <div class="d-flex align-items-center gap-3">

                        <a class="nav-link text-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            خروج
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        <div class="text-muted">
                            <strong>المستخدم الحالي:</strong> {{ auth()->user()->name }}
                        </div>

                        <div class="text-muted">
                            {{ $companyRec->name ?? '' }}
                            @if(session()->has('current_year'))
                                {{ ' | ' }} {{ session('current_year') }}
                            @endif
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </nav>

    {{-- Page Header --}}
    <div class="container mt-3">
        @yield('header')
    </div>

    {{-- Page Content --}}
    <div class="container mt-3">
        @yield('content')
    </div>

    {{-- Bootstrap Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>

</html>