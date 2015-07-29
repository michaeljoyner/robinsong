@extends('admin.base')

@section('content')
    <h1>Register a user</h1>
    @include('errors')
    @include('admin.forms.register')
@endsection