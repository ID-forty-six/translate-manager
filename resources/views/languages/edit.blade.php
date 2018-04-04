@extends('adminlte::page')

@section('title', 'Languages')

@section('content_header')
    <h1>Edit language</h1>
@stop

@section('content')

<div class="box box-primary">
    
    <form role="form" action={{ route('languages.update', $language) }} method='POST'>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="box-body">
            <div class="form-group">
                <label for="name">Short</label>
                <input type="text" class="form-control" id="short" name="short"  value="{{ $language->short }}" placeholder="Enter language shortage" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $language->name }}" placeholder="Enter language name" required>
            </div>
            <div class="form-group">
                <label for="name">Name ascii</label>
                <input type="text" class="form-control" id="name_ascii" name="name_ascii" value="{{ $language->name_ascii }}" placeholder="Enter language name ascii" required>
            </div>
        </div>
    
        <div class="box-footer">
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </form>
</div>

<a href={{ route('languages.index') }} class="btn btn-lg btn-primary">Back</a>
          
@stop