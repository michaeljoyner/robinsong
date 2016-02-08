@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">Robin Song Staff</h1>
        <div class="rs-header-actions pull-right">
            <a href="/admin/register">
                <div class="btn rs-btn btn-orange">
                    Add user
                </div>
            </a>
        </div>
        <hr>
        @foreach($users as $user)
            <div class="user-profile-card">
                <header class="user-profile-card-header">
                    <h4>{{ $user->name }}</h4>
                </header>
                <div class="user-profile-card-body">
                    <p>{{ $user->email }}</p>
                </div>
                <footer class="user-profile-card-footer clearfix">
                    <div class="user-actions pull-right">
                        <a href="/admin/users/{{ $user->id }}/edit">
                            <div class="btn rs-btn btn-light">
                                Edit
                            </div>
                        </a>
                        @include('admin.partials.deletebutton', ['objectName' => $user->name, 'deleteFormAction' => '/admin/users/'.$user->id])
                    </div>
                </footer>
            </div>
        @endforeach
    </div>
    @include('admin.partials.deletemodal')
@endsection

@section('bodyscripts')
    @include('admin.partials.modalscript')
@endsection