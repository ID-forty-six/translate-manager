@extends('adminlte::page')

@section('title', 'Languages')

@section('content_header')
    <h1>Create new language</h1>
@stop

@section('content')

<div class="box box-primary">
    
    <form role="form" action={{ route('languages.store') }} method='POST'>
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="name">Id</label>
                <input type="text" class="form-control" id="id" name="id" placeholder="Enter language id" required>
            </div>
            <div class="form-group">
                <label for="name">Short</label>
                <input type="text" class="form-control" id="short" name="short" placeholder="Enter language shortage" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter language name" required>
            </div>
            <div class="form-group">
                <label for="name">Name ascii</label>
                <input type="text" class="form-control" id="name_ascii" name="name_ascii" placeholder="Enter language name ascii" required>
            </div>
            <div class="form-group">
                <label for="name">Status</label>
                <input type="number" class="form-control" id="status" name="status" placeholder="Enter language status" required>
            </div>
        </div>
    
        <div class="box-footer">
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </form>
</div>

<a href={{ route('projects.index') }} class="btn btn-lg btn-primary">Back</a>
          
@stop