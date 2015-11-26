@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1>Create a new Product</h1>
    </div>
    @include('admin.forms.product', [
        'model' => $product,
        'formAction' => '/admin/categories/'.$categoryId.'/products',
        'buttonText' => 'Add Product'
    ])
@endsection