{!! Form::open(['url' => '/admin/users/password/reset', 'class' => 'rs-form form-horizontal form-narrow']) !!}
    @include('errors')
    <div class="form-group">
        <label for="current_password">Current password: </label>
        {!! Form::password('current_password', ['class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        <label for="password">New Password: </label>
        {!! Form::password('password', ['class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirm New Password: </label>
        {!! Form::password('password_confirmation', ['class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        <button type="submit" class="btn rs-btn">Reset Password</button>
    </div>
{!! Form::close() !!}