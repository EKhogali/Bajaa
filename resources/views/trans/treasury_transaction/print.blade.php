@extends('layout.master')
@section('content')
    <div class="container" style="border: 2px solid #000000; padding: 10px; margin: 5px;">
        @if($treasury_transaction->trans_type == 0)
            <div class="container">
                <h3>تفاصيل إيصال قبض</h3>
            </div>
        @else
            <div class="container">
                <h3>تفاصيل إيصال صرف</h3>
            </div>
        @endif

        <table class="table">
            <tr>
                <th>رقم آلي</th>
                <td>{{ $treasury_transaction->id }}</td>
            </tr>
            <tr>
                <th>الرقم اليدوي</th>
                <td>{{ $treasury_transaction->manual_no }}</td>
            </tr>
            <tr>
                <th>التاريخ</th>
                <td>{{ \Carbon\Carbon::parse($treasury_transaction->date)->format('Y-m-d') }}</td>
            </tr>
            <tr>@dd('======11111');
                <th>الحساب</th>
                <td>{{ $treasury_transaction->account->name }}</td>
            </tr>
            <tr>
                <th>الخزينة</th>
                <td>{{ $treasury_transaction->treasury->name }}</td>
            </tr>
            <tr>
                <th>القيمة</th>
                <td>{{ number_format($treasury_transaction->amount, 2) }}</td>
            </tr>
            <tr>
                <th>القيمة بالحروف</th>
                <td>{{ \Alkoumi\LaravelArabicTafqeet\Tafqeet::inArabic($treasury_transaction->amount,'ld') }}</td>
            </tr>
            <tr>
                <th>الوصف</th>
                <td>{{ $treasury_transaction->description }}</td>
            </tr>
            <tr>
                <th>وسم</th>
                <td>{{ $treasury_transaction->tag_id == 1 ? 'مسحوبات' : '/' }}</td>
            </tr>
        </table>
        <button class="btn btn-success" onclick="window.print()">طباعة</button>
    </div>
@endsection
