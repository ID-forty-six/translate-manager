@extends('adminlte::page')

@section('title', 'Sources')

@section('content_header')
    <h1>Sources</h1>
@stop

@section('content')
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
        @foreach( $languages as $language )
            <tr>
                <td>{{ $language->id }}</td>
                <td>{{ $language->short }}</td>
                <td>{{ $language->name_ascii }}</td>
            </tr> 
        @endforeach 
    </table> 
@stop