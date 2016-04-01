@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $category->name }}</h1>
        <div class="rs-header-actions pull-right">
            <a href="/admin/categories/{{ $category->id }}/edit">
                <div class="btn rs-btn btn-light">Edit</div>
            </a>
            @include('admin.partials.deletebutton', [
                'objectName' => $category->name,
                'deleteFormAction' => '/admin/categories/'.$category->id
            ])
        </div>
        <hr>
    </div>
    <div class="row collection-summary">
        <div class="col-md-8">
            <p class="lead">{{ $category->description }}</p>
        </div>
        <div id="cover-pic-vue" class="col-md-4 single-image-uploader-box">
            <singleupload default="{{ $category->coverPic('thumb') }}"
                          url="/admin/uploads/categories/{{ $category->id }}/cover"
                          shape="square"
                          size="small"
            ></singleupload>
        </div>
    </div>
    <section class="product-index">
        <div class="rs-section-header">
            <h2 class="pull-left">{{ $category->name }} products</h2>
            <div class="section-actions pull-right">
                <button class="btn rs-btn btn-orange" data-toggle="modal" data-target="#product-form-modal">
                    New Product
                </button>
            </div>
            <hr>
        </div>
        @foreach($category->products as $product)
            <a href="/admin/products/{{ $product->id }}"><div class="index-card smaller-index">
                <h4 class="index-card-name">{{ $product->name }}</h4>
                <img src="{{ $product->coverPic('thumb') }}" alt="product image">
                <hr>
                <p class="index-card-description">{{ $product->description }}</p>
            </div></a>
        @endforeach
    </section>
    @include('admin.forms.productmodal', [
        'model' => $newProduct,
        'formAction' => '/admin/categories/'.$category->id.'/products',
        'buttonText' => 'Add Product'
    ])
@endsection
@include('admin.partials.deletemodal')

@section('bodyscripts')
    <script>
        new Vue({
            el: '#cover-pic-vue'
        });
    </script>
    @include('admin.partials.modalscript')
@endsection