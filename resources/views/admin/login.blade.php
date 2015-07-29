@extends('admin.base')

@section('content')
    <h1>Login</h1>
    @include('errors')
    @include('admin.forms.login')
@endsection