@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1>Create a new Category</h1>
    </div>
    @include('admin.forms.category', [
        'model' => $category,
        'formAction' => '/admin/collections/'.$collectionId.'/categories',
        'buttonText' => 'Add category'
    ])
@endsection