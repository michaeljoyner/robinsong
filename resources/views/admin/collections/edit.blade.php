@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1>Edit {{ $collection->name }}</h1>
    </div>
    @include('admin.forms.collection', [
        'model' => $collection,
        'formAction' => '/admin/collections/'.$collection->id,
        'buttonText' => 'Save Changes'
    ])
@endsection