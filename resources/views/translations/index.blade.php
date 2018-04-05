@extends('adminlte::page')

@section('title', 'Translations')

@section('content_header')
    <h1>Translations</h1>
@stop

@section('content')

@include('partials.message')
@include('partials.errors')
<div class="box-footer">
        <a href="{{ route('translations.publish') }}" class="btn btn-success pull-right btn-lg">Publish translations</a>
</div>

<div class="box">
      <form role="form" action={{ route('translations.index') }} method='GET'>
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="project_id">Project</label>
                <select id="project_id" name="project_id" class="form-control">
                    @foreach( $projects as $project )
                        <option value="{{ $project->id }}" {{ $project->id == session()->get('project_id') ? "selected" : ""}}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="from_lang_id">Translate From</label>
                <select id="from_lang_id" name="from_lang_id" class="form-control">
                    @foreach( $languages as $language )
                        <option value="{{ $language->id }}" {{ $language->id == session()->get('from_lang_id') ? "selected" : ""}}>{{ $language->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="to_id">Translate To</label>
                <select id="to_lang_id" name="to_lang_id" class="form-control">
                    @foreach( $languages as $language )
                        <option value="{{ $language->id }}" {{ $language->id == session()->get('to_lang_id') ? "selected" : ""}}>{{ $language->id }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    
        <div class="box-footer">
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </form>
   
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>{{ session()->get('from_lang_id') }} Key</th>
            <th>{{ session()->get('to_lang_id') }} Translation</th>
        </tr>
            
        @foreach( $keys as $key=>$value)
                
        <tr>
            <td>{{ $value['id'] }}</td>
            <td>{{ $value['key'] }}</td>
            <td>
                <p>test{{ $value['translation']['id'] }}</p>
                <form role="form" action={{ route('translations.findOrCreate') }} method='post'>
                            
                    {{ csrf_field() }}
                        
                    <input type="hidden" id="source_id"  name="source_id" value="{{ $value['id'] }}">
                    <input type="hidden" id="translation_id"  name="translation_id" value="{{ $value['translation']['id'] }}">
                            
                    <div class="input-group input-group-lg">
                        <input type="text" id="translation" name="translation" class="form-control input-lg" value="{{ $value['translation']['translation'] }}">
                            
                        <div class="input-group-btn">
                            @if($value['translation']['is_published'] === 0 )
                           
                                <button type="submit" class="btn btn-success">Save (unpublished)</button>
                                    
                            @else
                                <button type="submit" class="btn btn-danger">Save</button>
                            @endif
                        </div>
                            
                    </div>
                        
                </form>
            </td>
        </tr>
            
        @endforeach
            
    </table>
</div>

@stop
