@if (count($errors))
    @foreach($errors as $error)
        <p class="alert alert-danger">{{ $error }}</p>
    @endforeach
@endif