@extends('admin.base')

@section('content')
    <h1>Reset Your Password</h1>
    <p class="lead">Reset the password for {{ Auth::user()->email }}</p>
    @include('admin.forms.passwordreset')
@endsection