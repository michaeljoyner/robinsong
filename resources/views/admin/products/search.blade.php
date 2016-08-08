@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@endsection

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">Products</h1>

        <div class="rs-header-actions pull-right">
            <div class="form-inline" style="width: 350px;">
                <div class="input-group">
                    <input type="text" class="form-control" v-model="term" v-on:keypress.enter="fetchResults" placeholder="search for...">
                    <div class="input-group-addon"><i class="glyphicon glyphicon-search"></i></div>
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div class="product-search-results-section">
        <ul class="results-list">
            <li v-for="result in results">
                <img :src="result.thumb" alt="thumbnail">
                <a href="/admin/products/@{{ result.id }}" class="result-name">@{{ result.name }}</a>
                <span class="result-price">@{{ result.category }}</span>
            </li>
        </ul>
        <ul class="results-list" v-if="freshpage">
            @foreach($products as $product)
                <li>
                    <img src="{{ $product->coverPic('thumb') }}" alt="thumbnail">
                    <a href="/admin/products/{{ $product->id }}" class="result-name">{{ $product->name }}</a>
                    <span class="result-price">{{ $product->category->name }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('bodyscripts')
    <script>
        var searchApp = new Vue({
            el: 'body',

            data: {
                term: '',
                results: [],
                freshpage: true
            },

            methods: {
                fetchResults: function() {
                    if(this.term === '') {
                        return;
                    }

                    this.$http.get('/admin/api/products/search/' + this.term, function(res) {
                        this.$set('results', res);
                        this.freshpage = false;
                    });
                    this.term = '';
                }
            }
        });
    </script>
@endsection