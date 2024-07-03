@extends('layout.master')
@section('content')
    <!doctype html>
<html lang="en">
<style>
    body{
        /*background-image: url('images\bg03.jpg');*/
        background-repeat: no-repeat;
        background-position-x: 0;
        background-position-y: 0;
        background-size: 100%;
    }
</style>
{{--<body background="images\la2.jpg" >--}}
<body  >
<br>
<div class="container">
    <h3 >About | حول</h3>
</div>
<br>
<div class="container-fluid row" >
    <div class="container col-4"></div>
    <div class="container col-4">
        <img src="\images\academy.png" alt="">
    </div>
    <div class="container col-4"></div>
</div>
<br>
<div class="container row">
    <div class="container col-4"></div>
    <div class="container col-4">
        <p>
            <strong>
                The Libyan Academy for Postgraduate Studies
                School of Applies sciences and Engineering
                Department of Electrical and Computer Engineering
                Information Technology Branch
            </strong>
        </p>
    </div>
    <div class="container col-4"></div>
</div>
<br>
<div class="container row">
    <div class="container col-4"></div>
    <div class="container col-4">
        <strong>Assignment 	:</strong> Assignment IIII <br>
        <strong>Subject	:</strong> web application as a Final Project <br>
        <strong>Instructor 	:</strong> Mohammed Elbeshti <br>
        <strong>Student 	:</strong> Elmothana Elmobarak <br>
        <strong>Academy ID	:</strong> 210100163 <br>
        <strong>Term	:</strong> Fall 2021

    </div>
    <div class="container col-4"></div>
</div>
<br>
<div class="container row">
    <div class="container col-4"></div>
    <div class="container col-4"></div>
    <div class="container col-4"></div>
</div>






</body>
</html>

@endsection

