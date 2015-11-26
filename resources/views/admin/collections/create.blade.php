@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1>Create a new Collection</h1>
    </div>
    @include('admin.forms.collection', [
        'model' => $collection,
        'formAction' => '/admin/collections',
        'buttonText' => 'Add Collection'
    ])
@endsection