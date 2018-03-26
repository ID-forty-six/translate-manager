@extends('adminlte::page')

@section('title', 'Sources')

@section('content_header')
    <h1>Languages</h1>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Languages</h3>
    </div>
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Progress</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            @foreach( $languages as $language )
                <tr>
                    <td>{{ $language->id }}</td>
                    <td>{{ $language->name_ascii }}</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                            <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                        </div>
                        <span class="badge bg-green">90%</span>
                    </td>
                    <td>
                        @if( $language->status == 'active')
                            <p>Active</p>
                        @else
                            <p>Disabled</p>
                        @endif
                    </td>
                    <td>
                        <a href={{ route('languages.edit', $language) }} class="btn btn-primary"><i class="fa fa-book"></i>translate</a><br>
                    </td>
                    <td>
                        <a href={{ route('languages.edit', $language) }} class="btn btn-warning"><i class="fa fa-pencil"></i>edit</a><br>
                    </td>
                    <td>
                        <form action="{{ route('languages.destroy', $language) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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

<a href="{{ route('languages.create') }}" class="btn btn-lg btn-danger">Add new language</a>

@stop