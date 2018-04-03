@extends('adminlte::page')

@section('title', 'Sources')

@section('content_header')
    <h1>Sources</h1>
@stop

@section('content')

@if( session()->has('message') )
    <div class="box-body no-padding">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>  
    </div> 
@endif

<div class="box">
    <div class="box-body">
        <a href="{{ route('findSources') }}" class="btn btn-danger btn-lg">Scan all projects</a>
    </div>
</div>

@stop