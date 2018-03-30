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
   <form role="form" action={{ route('findSources') }} method='post'>
        {{ csrf_field() }}
              <div class="form-group">
                  <label for="project_id">Project</label>
                  <select id="project_id" name="project_id" class="form-control">
                      
                      @foreach( $projects as $project )
                      
                      <option value="{{ $project->id }}" {{ $project->id == session()->get('project_id') ? "selected" : ""}}>{{ $project->name }}</option>
                      
                      @endforeach
                </select>
            </div>
            <div class="box-footer">
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </form>
</div>
</div>

@stop