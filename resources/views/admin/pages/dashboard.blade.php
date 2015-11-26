@extends('admin.base')

@section('content')
    <h1>Hello Clarice</h1>
    <button id="new-collection-trigger" data-toggle="modal" data-target="#collection-form-modal">
        add collection
    </button>
    @include('admin.forms.collectionmodal', [
        'model' => $collection,
        'formAction' => '/admin/collections',
        'buttonText' => 'Add Collection'
    ])
@endsection

@section('bodyscripts')

@endsection