@extends('adminlte::page')

@section('title', 'Sources')

@section('content_header')
    <h1>Sources</h1>
@stop

@section('content')
    <table>
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
@stop