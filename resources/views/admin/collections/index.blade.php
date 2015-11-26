@extends('admin.base')

@section('content')
    <div class="rs-page-header clearfix">
        <h1 class="pull-left">Your Collections</h1>
        <div class="rs-header-actions pull-right">
            <button class="btn rs-btn btn-orange" data-toggle="modal" data-target="#collection-form-modal">
                New Collection
            </button>
        </div>
        <hr>
    </div>
    @foreach($collections as $collection)
        <div class="collection-index-card">
            <h3>{{ $collection->name }}</h3>
        </div>
    @endforeach
    @include('admin.forms.collectionmodal', [
        'model' => $newCollection,
        'formAction' => '/admin/collections',
        'buttonText' => 'Add Collection'
    ])
@endsection