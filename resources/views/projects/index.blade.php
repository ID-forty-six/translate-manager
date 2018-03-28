@extends('adminlte::page')

@section('title', 'Projects')

@section('content_header')
    <h1>Projects</h1>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Projects</h3>
    </div>
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Framework</th>
                <th>Path</th>
                <th>Actions</th>
            </tr>
            @foreach( $projects as $project )
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->framework }}</td>
                    <td>{{ $project->path }}</td>
                    <td>
                        <a href={{ route('projects.edit', $project) }} class="btn btn-warning"><i class="fa fa-pencil"></i>edit</a><br>
                    </td>
                    <td>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="_method" value="DELETE">
                            {{ csrf_field() }}
                            <button class="btn btn-danger"><i class="fa fa-trash-o"></i>delete</button>
                        </form>
                    </td> 
                </tr> 
            @endforeach
        </table> 
    </div>
</div>

<a href="{{ route('projects.create') }}" class="btn btn-lg btn-danger">Add new project</a>

@stop