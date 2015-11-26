@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1>Edit {{ $category->name }}</h1>
    </div>
    @include('admin.forms.category', [
        'model' => $category,
        'formAction' => '/admin/categories/'.$category->id,
        'buttonText' => 'Save Changes'
    ])
@endsection