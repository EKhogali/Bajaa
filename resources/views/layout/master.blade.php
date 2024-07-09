<!doctype html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Add Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required for Select2) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Add Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>


    <title>المحاسبة الرقمية | Digital Accounting</title>

    <script type="text/javascript">
        function sh(v){
            alert(v)
        }
    </script>
</head>
<body>



<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
{{--        <a class="navbar-brand" href="/">Digital Accounting | المحاسبة الرقمية</a>--}}
{{--        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">الرئيسية</a>
                </li>
                @if(Auth::check() && \session()->has('company_id'))

                @if(auth()->id() == 1 or auth()->id() == 2)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 البيانات الأساسية
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/categories">مجموعات الحسابات</a></li>
                                <li><a class="dropdown-item" href="/accounts?acc_type={{0}}" id="acc0" onclick="changeAccType(0)">دليل الحسابات</a></li>
                                <li><a class="dropdown-item" href="/accounts?acc_type={{1}}" id="acc1" onclick="changeAccType(1)">دليل الاصول الثابتة</a></li>
                                <li><a class="dropdown-item" href="/accounts?acc_type={{2}}" id="acc2" onclick="changeAccType(2)">دليل الحسابات التفصيلية</a></li>
                                <li><a class="dropdown-item" href="/companies">الشركات / المؤسسات</a></li>
                                <li><a class="dropdown-item" href="/treasuries">الخزائن</a></li>
                                <li><a class="dropdown-item" href="/partners">الشركاء</a></li>
                            </ul>
                        </li>
                @endif
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         الحركـــــة
                    </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
    {{--                        <li><a class="dropdown-item" href="/journals">القيود المحاسبية</a></li>--}}
    {{--                        <li><hr class="dropdown-divider"></li>--}}
                            <li><a class="dropdown-item" href="/treasury_transaction?trans_type={{0}}">ايصالات القبض</a></li>
                            <li><a class="dropdown-item" href="/treasury_transaction?trans_type={{1}}">ايصالات الصرف</a></li>
    {{--                        <li><a class="dropdown-item" href="/treasury_transaction?transaction_type_id={{1}}">التحويلات</a></li>--}}
                        </ul>
                    </li>
                    @if(auth()->id() == 1 or auth()->id() == 2)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             التقاريــر
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
    {{--                        <li><a class="dropdown-item" href="/gl_index">الأستــــاذ العام</a></li>--}}
    {{--                        <li><a class="dropdown-item" href="/l_index">الأستــــاذ المساعد</a></li>--}}
    {{--                        <li><a class="dropdown-item" href="/tr_index">ميزان المراجعـــة</a></li>--}}
                            <li><a class="dropdown-item" href="/income_report">تقرير الدخل</a></li>
                            <li><a class="dropdown-item" href="/account_details_report">تقرير الحركة التفصيلية لحساب</a></li>
                        </ul>
                    </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        إدارة النظام
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/sitting">إعدادات النظام</a></li>
                        <li><a class="dropdown-item" href="/users">المستخدمين والصلاحيات</a></li>
                    </ul>
                </li>

                @endif
{{--                <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                        System--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
{{--                        <li><a class="dropdown-item" href="#">User Management</a></li>--}}
{{--                        <li><a class="dropdown-item" href="/about">About</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link " href="#">User Management</a>--}}
{{--                </li>--}}
                @endif

{{--                    <a class="nav-link " href="/about">حول النظام</a>--}}

            </ul>

                @if(Auth::check() )
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">خروج</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>



                    &nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp
                    <label for="auth"><strong>المستخدم الحالي:</strong> </label>
                    <label id="auth">{{auth()->user()->name}}</label>

                &nbsp&nbsp &nbsp&nbsp &nbsp
{{--                <label for="auth"><strong>الشركة / السنة المالية: </strong> </label>--}}
                {{$companyRec->name ?? ''}}
                {{' | ' }}
                {{\Session('current_year') ?? ''}}
            @endif
{{--Cuurent Company & F Year: {{session()->get('$company_name')}}--}}
{{-- $company_name.' | '.$financial_year}}--}}
{{--            {{\Carbon\Carbon::now()->format('y-m-d D')}}--}}
{{--            <form class="d-flex">--}}
{{--                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">--}}
{{--                <button class="btn btn-outline-success" type="submit">Search</button>--}}
{{--            </form>--}}
        </div>
    </div>
</nav>

    <div class="container">
        @yield('header')
    </div>
    <div class="container">
        @yield('content')
    </div>
{{--<div class="container fluid">--}}
{{--    <img src="bg01.jpg" class="img-fluid" alt="logo">--}}
{{--    <img src="/images/bg04.jpeg" alt="logo">--}}
{{--</div>--}}




<script>
    function changeAccType(newAccType) {
        var link = document.getElementById('specialLink');
        var href = link.getAttribute('href');
        href = href.replace(/acc_type=\d+/, 'acc_type=' + newAccType);
        link.setAttribute('href', href);

        // Optionally, you can navigate to the modified URL
        // window.location.href = href;
    }
</script>


{{--    @yield('footer')--}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
