@extends('admin.base')

@section('content')
    <h1>Edit User Details</h1>
    @include('errors')
    @include('admin.forms.edituser')
@endsection