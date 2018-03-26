@extends('adminlte::page')

@section('title', 'Sources')

@section('content_header')
    <h1>Create new project</h1>
@stop

@section('content')

<div class="box box-primary">
    
    <form role="form" action={{ route('projects.update', $project) }} method='POST'>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="box-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $project->name }}" placeholder="Enter project name" required>
            </div>
            <div class="form-group">
                <label for="framework">Framework</label>
                <select id="framework" name="framework" class="form-control">
                    <option selected>{{ $project->framework }}</option>
                    @foreach(config('app.frameworks') as $framework)
                        <option>{{ $framework }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="path" >Path</label>
                <input type="text" class="form-control" id="path" name="path" value="{{ $project->path }}" placeholder="Enter project path" required>
            </div>
        </div>
    
        <div class="box-footer">
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </form>
</div>

<a href={{ route('projects.index') }} class="btn btn-lg btn-primary">Back</a>
          
@stop