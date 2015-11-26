@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $category->name }}</h1>
        <div class="rs-header-actions pull-right">
            <button class="btn rs-btn btn-orange" data-toggle="modal" data-target="#product-form-modal">
                New Product
            </button>
        </div>
        <hr>
    </div>
    <p class="lead">{{ $category->description }}</p>
    <section class="product-index">
        @foreach($category->products as $product)
            <div class="product-index-card">
                <h4 class="product-index-card-name">{{ $product->name }}</h4>
                <hr>
                <p class="product-index-card-description">{{ $product->description }}</p>
            </div>
        @endforeach
    </section>
    @include('admin.forms.productmodal', [
        'model' => $newProduct,
        'formAction' => '/admin/categories/'.$category->id.'/products',
        'buttonText' => 'Add Product'
    ])
@endsection