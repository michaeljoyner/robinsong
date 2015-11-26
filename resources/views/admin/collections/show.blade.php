@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $collection->name }}</h1>
        <div class="rs-header-actions pull-right">
            <button class="btn rs-btn btn-orange" data-toggle="modal" data-target="#category-form-modal">
                New Category
            </button>
        </div>
        <hr>
    </div>
    <p class="lead">{{ $collection->description }}</p>
    <section class="category-index">
        @foreach($collection->categories as $category)
            <div class="category-index-card">
                <a href="/admin/categories/{{ $category->id }}">
                    <h4 class="category-index-card-name">{{ $category->name }}</h4>
                </a>
                <hr>
                <p class="category-index-card-description">{{ $category->description }}</p>
            </div>
        @endforeach
    </section>
    @include('admin.forms.categorymodal', [
        'model' => $newCategory,
        'formAction' => '/admin/collections/'.$collection->id.'/categories',
        'buttonText' => 'Add Category'
    ])
@endsection