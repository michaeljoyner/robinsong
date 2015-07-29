{!! Form::model($user, ['url' => 'admin/users/'.$user->id.'/edit', 'class' => 'form-horizontal']) !!}
<div class="form-group">
    <label for="name">Name: </label>
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label for="email">Email: </label>
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <button type="submit" class="btn">Save</button>
</div>
{!! Form::close() !!}