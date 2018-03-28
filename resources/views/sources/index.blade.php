@extends('adminlte::page')

@section('title', 'Sources')

@section('content_header')
    <h1>Sources</h1>
@stop

@section('content')
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
<div class="box">
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Group</th>
                <th>project</th>
            </tr>
            @foreach( $sources as $source )
                <tr>
                    <td>{{ $source->id }}</td>
                    <td>{{ $source->key }}</td>
                    <td>{{ $source->group }}</td>
                    <td>{{ $source->project->name }}</td>
                </tr> 
            @endforeach 
        </table>
    </div>
</div>

@stop