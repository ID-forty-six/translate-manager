@if( session()->has('message') )
    <div class="box-body no-padding">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>  
    </div> 
@endif