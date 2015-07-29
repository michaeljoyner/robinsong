{!! Form::open(['url' => 'admin/login', 'class' => 'form-horizontal']) !!}
<div class="form-group">
    <label for="email">Email address: </label>
    <input type="email" name="email" value="{{ Input::old('email') }}" class="form-control"/>
</div>
<div class="form-group">
    <label for="password">Password: </label>
    <input type="password" name="password" class="form-control"/>
</div>
<div class="form-group">
    <button type="submit" class="btn">Login</button>
</div>
{!! Form::close() !!}