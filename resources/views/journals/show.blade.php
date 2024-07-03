@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 ><a href="/journals">Journals</a> Details</h3>
</div>
<br>
<div class="container row">
    <div class="container col-4">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Field</th>
                <th scope="col">Value</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Journal ID</th>
                    <th scope="row">{{$journalm->id ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Date</th>
                    <th scope="row">{{Carbon\Carbon::parse($journalm->date)->format('Y-m-d') ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Code</th>
                    <th scope="row">{{$journalm->code ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Total Creditor</th>
                    <th scope="row">{{number_format($journalm->total_creditor,2) ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Total Debtor</th>
                    <th scope="row">{{number_format($journalm->total_debtor,2) ?? ''}}</th>
                </tr>
                <tr>
                    <th scope="row">Balanced</th>
                    <th scope="row">
                            @if($journalm->balanced == 1)
                                Balanced
                            @else Not Balanced
                            @endif
                    </th>
                </tr>
                <tr>
                    <th scope="row">Reviewed</th>
                    <th scope="row">
                            @if($journalm->Reviewed == 1)
                                Reviewed
                            @else Not Reviewed
                            @endif
                    </th>
                </tr>
                <tr>
                    <th scope="row">Posted</th>
                    <th scope="row">
                            @if($journalm->Posted == 1)
                                Posted
                            @else Not Posted
                            @endif
                    </th>
                </tr>
                <tr>
                    <th scope="row">Description </th>
                    <th scope="row">{{$journalm->description ?? ''}}</th>
                </tr>
            </tbody>
        </table>
        <div class="d-grid gap-2 col mx-auto">
            {{--            <button class="btn btn-secondary" type="button"><a href="categories/create">Add Category</a></button>--}}
            <a class="btn btn-primary" href="/journald/create?journalm_id={{$journalm->id}}" role="button">Add Journal Details</a>
        </div>
        <br>
        <div class="d-grid gap-2 col mx-auto">
            <a class="btn btn-primary" href="/journals/create" role="button">Add Journal</a>
        </div>
        <br>
        <div class="d-grid gap-2 col mx-auto">
            <form method="post" class="delete_form" action="{{action('JournalmController@destroy', $journalm->id)}}">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="DELETE" />
                <button type="submit" class="btn btn-danger">Delete Journal</button>
            </form>
{{--            <a class="btn btn-danger" href="/journals/create" role="button">Delete Journal</a>--}}
        </div>
    </div>
    <div class="container col-8">
        <table class="table">
            <thead>
            <tr>
{{--                <th scope="col">#</th>--}}
                <th scope="col">Account</th>
                <th scope="col">Creditor</th>
                <th scope="col">Debtor</th>
                <th scope="col">Description</th>
                <th scope="col">edit</th>
                <th scope="col">delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($journalds as $journald)
                <tr>
{{--                    <th scope="row">{{$journald->id ?? ''}}</th>--}}
                    <th scope="row">{{$journald->accountR->name ?? ''}}</th>
                    <th scope="row">{{number_format($journald->credit_amount,2) ?? '0'}}</th>
                    <th scope="row">{{number_format($journald->debit_amount,2) ?? '0'}}</th>
                    <th scope="row">{{$journald->description ?? ''}}</th>
                    <th scope="row"><a href="/journald/{{$journald->id}}/edit" class="btn btn-warning">Edit</a></th>
                    <th scope="row"><form method="post" class="delete_form" action="{{action('JournaldController@destroy', $journald->id)}}">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="DELETE" />
                            <button type="submit" class="btn btn-danger">Del</button>
                        </form></th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>





{{-----------------------------------------------------------------------------------------------------}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



{{-----------------------------------------------------------------------------------------------------}}
</body>
</html>
@endsection

