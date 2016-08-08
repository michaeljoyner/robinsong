@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $product->name }} &mdash; Shipping Units</h1>
        <div id="toggle-available-app" class="rs-header-actions pull-right">
            <button type="button" class="btn rs-btn btn-clear" data-toggle="modal" data-target="#stockunit-modal">
                Add new
            </button>
            <a href="/admin/products/{{ $product->id }}" class="btn rs-btn btn-light">Back to Product</a>
        </div>
        <hr>
        <stockunits product-id="{{ $product->id }}"></stockunits>
    </div>
    @include('admin.forms.stockunitmodal', ['formAction' => '/admin/products/' . $product->id . '/stockunits'])
@endsection

@section('bodyscripts')
    <script>
        new Vue({el: 'body'});
    </script>
@endsection