@extends('adminlte::page')

@section('title', 'Export')

@section('content_header')
    <h1>Export</h1>
@stop

@section('content')

<div class="box box-primary">
    <div class="box-footer">
        <a href="{{ route('exportAction') }}" class="btn btn-lg btn-success">Export</a>
    </div>
</div>
          
@stop