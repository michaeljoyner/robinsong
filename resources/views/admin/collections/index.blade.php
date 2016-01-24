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
        <a href="/admin/collections/{{ $collection->id }}"><div class="index-card">
            <h3 class="index-card-name">{{ $collection->name }}</h3>
            <img src="{{ $collection->coverPic('thumb') }}" alt="collection image">
            <hr>
            <p class="index-card-description">{{ $collection->description }}</p>
        </div></a>
    @endforeach
    @include('admin.forms.collectionmodal', [
        'model' => $newCollection,
        'formAction' => '/admin/collections',
        'buttonText' => 'Add Collection'
    ])
@endsection