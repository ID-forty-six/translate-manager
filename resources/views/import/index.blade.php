@extends('adminlte::page')

@section('title', 'Import')

@section('content_header')
    <h1>Import</h1>
@stop

@section('content')

@include('partials.message')
@include('partials.errors')

<div class="box box-primary">
    <form role="form" action="{{ route('importAction') }}" method='POST' enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="upload">Select file</label>
                <input type="file" id="upload" name="upload" class="form-control" required>
            </div>
        </div>
    
        <div class="box-footer">
            <button type="submit" class="btn btn-danger btn-lg">Submit</button>
        </div>
    </form>
</div>
          
@stop