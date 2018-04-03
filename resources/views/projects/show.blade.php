@extends('adminlte::page')

@section('title', 'Projects')

@section('content_header')
    <h1>Projects</h1>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ $project->name }}</h3>
    </div>
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tr>
                <th>Language</th>
                <th>Progress Bar</th>
                <th>Translations %</th>
            </tr>
            @foreach( $languages as $language )
                <tr>
                    <td>{{ $language->id }}</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                            <div class="progress-bar progress-bar-success" style="width: {{ $data['percent_complete'][$language->id] }}%"></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-green">{{ $data['percent_complete'][$language->id] }}%</span>
                    </td>
                </tr> 
            @endforeach
        </table> 
    </div>
</div>

<a href="{{ route('projects.index') }}" class="btn btn-lg btn-danger">All projects</a>

@stop