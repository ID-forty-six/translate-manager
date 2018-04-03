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
                <label for="language_id">Language</label>
                <select id="language_id" name="language_id" class="form-control">
                    @foreach( $languages as $language )
                        <option value="{{ $language->id }}" {{ $language->id == session()->get('language_id') ? "selected" : ""}}>{{ $language->id }}</option>
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
                <th>Key</th>
                <th>Translation</th>
            </tr>
            
            @foreach( $sources as $source )
                <tr>
                    <td>{{ $source->id }}</td>
                    <td>{{ $source->key }}</td>
                    <td>
                        <form role="form" action={{ route('translations.findOrCreate') }} method='post'>
                            
                            {{ csrf_field() }}
                            <input type="hidden" id="source_id"  name="source_id" value="{{ $source->id }}">
                            <div class="input-group input-group-lg">
                                
                            @if($source->translations->isEmpty())
                                <input type="text" id="translation" name="translation" class="form-control input-lg">
                            @else
                                @foreach( $source->translations as $translation )
                                <input type="text" id="translation" name="translation" value="{{ $translation->translation }}" class="form-control input-lg">
                                <input type="hidden" id="translation_id" name="translation_id" value="{{ $translation->id }}">
                                
                                @endforeach
                            @endif
                                <div class="input-group-btn">
                                    @if(!$source->translations->isEmpty())
                                        @foreach( $source->translations as $translation )
                                            @if($translation->is_published == 0)
                                                <button type="submit" class="btn btn-success">Save (unpublished)</button>
                                            @else
                                                <button type="submit" class="btn btn-danger">Save</button>
                                            @endif
                                        @endforeach
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

@section('scripts')

<script>

function (jsonFile) 
{
    //var templateName = window.prompt("enter template name","");
            
        $.ajax({
            url: 'www.www.www',
            type: 'POST',
            data: {
                //jsonFile: jsonFile,
                //templateName: templateName,
            },
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (data) {
                alert('template saved');
            },
        });    
    
}
</script>

@stop