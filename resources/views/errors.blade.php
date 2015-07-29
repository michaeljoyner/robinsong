@if(count($errors) > 0)
    <div class="alert error-box">
        <h4 class="error-header">There were some problems with your input</h4>
        @foreach($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
        @endforeach
    </div>
@endif