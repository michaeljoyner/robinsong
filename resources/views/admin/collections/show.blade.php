@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $collection->name }}</h1>
        <div class="rs-header-actions pull-right">
            <a href="/admin/collections/{{ $collection->id }}/edit">
                <div class="btn rs-btn btn-light">Edit</div>
            </a>
            <a href="#">
                <div class="btn rs-btn btn-clear-danger">Delete</div>
            </a>
        </div>
        <hr>
    </div>
    <div class="row collection-summary">
        <div class="col-md-8">
            <p class="lead">{{ $collection->description }}</p>
        </div>
        <div id="cover-pic-vue" class="col-md-4 single-image-uploader-box">
            <singleupload default="{{ $collection->coverPic('thumb') }}"
                          url="/admin/uploads/collections/{{ $collection->id }}/cover"
                          shape="square"
                          size="small"
            ></singleupload>
        </div>
    </div>
    <section class="category-index">
        <div class="rs-section-header">
            <h2 class="pull-left">Categories in this collection</h2>
            <div class="section-actions pull-right">
                <button class="btn rs-btn btn-orange" data-toggle="modal" data-target="#category-form-modal">
                    New Category
                </button>
            </div>
            <hr>
        </div>
        @foreach($collection->categories as $category)
            <a href="/admin/categories/{{ $category->id }}"><div class="index-card smaller-index">
                <h4 class="index-card-name">{{ $category->name }}</h4>
                <img src="{{ $category->coverPic() }}" alt="category cover pic">
                <hr>
                <p class="index-card-description">{{ $category->description }}</p>
            </div></a>
        @endforeach
    </section>
    @include('admin.forms.categorymodal', [
        'model' => $newCategory,
        'formAction' => '/admin/collections/'.$collection->id.'/categories',
        'buttonText' => 'Add Category'
    ])
@endsection

@section('bodyscripts')
    <script>
        new Vue({
            el: '#cover-pic-vue'
        });
    </script>
@endsection