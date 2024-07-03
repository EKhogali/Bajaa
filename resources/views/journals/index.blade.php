@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">

<body>
<br>
<div class="container">
    <h3 >Journals</h3>
</div>
<br>
<div class="container row">
    <div class="container col-2">
        <div class="d-grid gap-2 col mx-auto">
{{--            <button class="btn btn-secondary" type="button"><a href="categories/create">Add Category</a></button>--}}
            <a class="btn btn-primary" href="journals/create" role="button">Add Journal</a>
        </div>
{{--        <br>--}}
{{--        <div class="d-grid gap-2 col mx-auto">--}}
{{--            <a class="btn btn-primary" href="journals/create" role="button"  data-bs-toggle="modal" data-bs-target="#addnewjournal">Add Journal 2</a>--}}
{{--        </div>--}}
    </div>
    <div class="container col-10">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Code</th>
                <th scope="col">Total Creditor</th>
                <th scope="col">Total Debtor</th>
                <th scope="col">Balanced</th>
                <th scope="col">Reviewed</th>
                <th scope="col">Posted</th>
                <th scope="col">Description</th>
                <th scope="col">edit</th>
{{--                <th scope="col">delete</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($journals as $journal)
                <tr>
                    <th scope="row"><a href="/journals/{{$journal->id}}">{{$journal->id ?? ''}}</a></th>
                    <th scope="row" width="11%">{{Carbon\Carbon::parse($journal->date)->format('Y-m-d') ?? ''}}</th>
{{--                    <th scope="row">{{$journal->date->format('Y-m-d') ?? ''}}</th>--}}
                    <th scope="row">{{$journal->code ?? ''}}</th>
                    <th scope="row">{{number_format($journal->total_creditor,2) ?? ''}}</th>
                    <th scope="row">{{number_format($journal->total_debtor,2) ?? ''}}</th>
                    <th scope="row">
                        @if($journal->balanced == 1)
                            Balanced
                        @else Not Balanced
                        @endif
                    </th>
                    <th scope="row">
                        @if($journal->Reviewed == 1)
                            Reviewed
                        @else Not Reviewed
                        @endif
                    </th>
                    <th scope="row">
                        @if($journal->Posted == 1)
                            Posted
                        @else Not Posted
                        @endif
                    </th>
                    <th scope="row">{{$journal->description ?? ''}}</th>
                    <th scope="row"><a href="journals/{{$journal->id}}/edit" class="btn btn-warning">Edit</a></th>
{{--                    <th scope="row"><form method="post" class="delete_form" action="{{action('JournalmController@destroy', $journal->id)}}">--}}
{{--                            {{csrf_field()}}--}}
{{--                            <input type="hidden" name="_method" value="DELETE" />--}}
{{--                            <button type="submit" class="btn btn-danger">الغاء</button>--}}
{{--                        </form>--}}
{{--                    </th>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>





{{-----------------------------------------------------------------------------------------------------}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<!-- Modal -->
<div class="modal fade" id="addnewjournal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Journal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container row">
                    <div class="container col-5 " >
                        <div class="container-fluid row ">
                            <div class="col-6">
                                <label for="date" class="form-label"><strong>Date</strong></label>
                                <input type="date" class="form-control" id="date" name="date"></div>
                        </div>
                        <br>
                        <div class="container-fluid row ">
                            <div class="col-6">
                                <label for="code" class="form-label"><strong>Code</strong> </label>
                                <input type="text" class="form-control" id="code" name="code[]">
                            </div>
                        </div>
                        <br>
                        <div class="container-fluid row ">
                            <div class="col">
                                <label for="description" class="form-label"><strong>Description</strong></label>
                                <input type="textarea" class="form-control" id="description" name="description" cols="40" rows="5">
                            </div>
                        </div>
{{--                        <table class="table table-bordered">--}}
{{--                            <tr>--}}
{{--                                <td class="col-5" ><strong>Code</strong></td>--}}
{{--                                <td class="col-7">121314</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>Date</strong></td>--}}
{{--                                <td>{{\Carbon\Carbon::now()->format('Y-m-d')}}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>Total Creditor</strong></td>--}}
{{--                                <td>121314</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>Total Debtor</strong></td>--}}
{{--                                <td>121314</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>Description</strong></td>--}}
{{--                                <td>any text</td>--}}
{{--                            </tr>--}}
{{--                        </table>--}}
                    </div>
                    <div class="container col-7 bg-info">
                        <div class="container1">
                            <button class="add_form_field">Add New Field &nbsp;
                                <span style="font-size:16px; font-weight:bold;">+ </span>
                            </button>
                            <div class="container">
                                <label for="account" name="acclabel[]" class="col-2">Account</label>
                                <select name="account_id[]" >
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}">
                                            {{$account->name}}
                                        </option>
                                    @endforeach
                                </select>
{{--                                <input type="text" name="mytext[]">--}}
                            </div>
                            <div class="container">
                                <label for="creditor" name="creditorlabel[]" class="col-2">Creditor</label>
                                <input type="text" name="creditor[]">
                            </div>
                            <div class="container">
                                <label for="debtor" name="debtorlabel[]" class="col-2">Debtor</label>
                                <input type="text" name="debtor[]">
                            </div>
                            <div class="container">
                                <label for="description" name="descriptionlabel[]" class="col-2">Description</label>
                                <input type="textarea" name="description[]" class="col-9">
                            </div>
                        </div>

                        <script>
                            $(document).ready(function() {
                                var max_fields = 100;
                                var wrapper = $(".container1");
                                var add_button = $(".add_form_field");

                                var x = 1;
                                $(add_button).click(function(e) {
                                    e.preventDefault();
                                    if (x < max_fields) {
                                        x++;
                                        $(wrapper).append(
                                            '<div>' +
                                            // '<input type="text" name="mytext[]"/>' +
                                            '<a href="#" class="delete">Delete</a>' +
                                            '<div class="container">'+
                                                '<label for="account" name="acclabel[]" class="col-2">Account</label>'+
                                                '<select name="account_id[]" >'+
                                                        '@foreach($accounts as $account)'+
                                                    '<option value="{{$account->id}}">'+
                                                        '{{$account->name}}'+
                                                    '</option>'+
                                                    '@endforeach'+
                                                '</select>'+
                                                {{--                                <input type="text" name="mytext[]">--}}
                                            '</div>'+
                                            '</div>'+
                                            '<div class="container">'+
                                               '<label for="creditor" name="creditorlabel[]" class="col-2">Creditor</label>'+
                                                '<input type="text" name="creditor[]">'+
                                            '</div>'+
                                        '<div class="container">'+
                                            '<label for="debtor" name="debtorlabel[]" class="col-2">Debtor</label>'+
                                            '<input type="text" name="debtor[]">'+
                                        '</div>'+
                                        '<div class="container">'+
                                            '<label for="description" name="descriptionlabel[]" class="col-2">Description</label>'+
                                            '<input type="textarea" name="description[]" class="col-9">'+
                                        '</div>'
                                        ); //add input box
                                    } else {
                                        alert('You Reached the limits')
                                    }
                                });

                                $(wrapper).on("click", ".delete", function(e) {
                                    e.preventDefault();
                                    $(this).parent('div').remove();
                                    x--;
                                })
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
{{-----------------------------------------------------------------------------------------------------}}
</body>
</html>
{{--{{$journals->links()}}--}}
@endsection

