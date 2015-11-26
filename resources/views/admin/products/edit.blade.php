@extends('admin.base')

@section('content')
    <div class="rs-page-header">
        <h1>Edit {{ $product->name }}</h1>
    </div>
    @include('admin.forms.product', [
        'model' => $product,
        'formAction' => '/admin/products/'.$product->id,
        'buttonText' => 'Save Changes'
    ])
@endsection