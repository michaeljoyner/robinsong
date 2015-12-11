{!! Form::model($model, ['url' => $formAction, 'class' => 'rs-form form-horizontal']) !!}
@include('errors')
<div class="form-group">
    <label for="name">Name: </label>
    {!! Form::text('name', null, ['class' => "form-control"]) !!}
</div>
<div class="form-group">
    <label for="description">Description: </label>
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>
<div class="row">
    <div class="col-sm-5">
        <div class="form-group">
            <label for="price">Price: </label>
            <div class="input-group">
                <span class="input-group-addon">&pound;</span>
                {!! Form::text('price', null, ['class' => "form-control", 'required' => 'true']) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-offset-2 col-sm-5">
        <div class="form-group">
            <label for="weight">Weight: </label>
            <div class="input-group">
                {!! Form::text('weight', null, ['class' => "form-control"]) !!}
                <span class="input-group-addon">g</span>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn rs-btn">{{ $buttonText }}</button>
</div>
{!! Form::close() !!}