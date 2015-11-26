{!! Form::model($model, ['url' => $formAction, 'class' => 'rs-form']) !!}
<div class="form-group">
    <label for="name">Name: </label>
    {!! Form::text('name', null, ['class' => "form-control"]) !!}
</div>
<div class="form-group">
    <label for="description">Description: </label>
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <button type="submit" class="btn">{{ $buttonText }}</button>
</div>
{!! Form::close() !!}