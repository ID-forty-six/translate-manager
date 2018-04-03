@extends('adminlte::page')

@section('title', 'Export')

@section('content_header')
    <h1>Export</h1>
@stop

@section('content')

@include('partials.message')
@include('partials.errors')

<div class="box box-primary">
    <form role="form" action="{{ route('exportAction') }}" method='POST'>
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="language_id">Language</label>
                <select id="language_id" name="language_id" class="form-control">
                    @foreach( $languages as $language )
                        <option value="{{ $language->id }}" {{ $language->id == session()->get('language_id') ? "selected" : ""}}>{{ $language->id }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    
        <div class="box-footer">
            <button type="submit" class="btn btn-danger btn-lg">Submit</button>
        </div>
    </form>
</div>
          
@stop