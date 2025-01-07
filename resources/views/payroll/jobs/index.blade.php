@extends('layout.master')
@section('content')
    <div class="container mt-4">
        <h3>الوظائف</h3>

        @if( session()->has('message') )
            @if( session()->has('msgtype') )
                @if( session()->get('msgtype') == 'success' )
                    <div class="alert alert-success">
                        @elseif(session()->get('msgtype') == 'notsuccess' )
                            <div class="alert alert-danger">
                                @endif
                                @endif
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <br>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>قائمة الوظائف</h4>
            <a href="{{ route('jobs.create') }}" class="btn btn-primary">إضافة وظيفة</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>الرقم</th>
                    <th>الاسم</th>
                    <th>أرشيف</th>
                    <th>إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td width="5%">{{ $job->id }}</td>
                        <td>{{ $job->name }}</td>
                        <td width="10%">
                            @if($job->archived)
                                <span class="badge bg-danger">مؤرشف</span>
                            @else
                                <span class="badge bg-success">لا</span>
                            @endif
                        </td>
                        <td width="10%">
                            <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline">
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
@endsection
