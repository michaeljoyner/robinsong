@extends('front.base')

@section('head')
    @include('front.partials.ogmeta', [
        'ogImage' => '/images/assets/logo_robinsong.png',
        'ogTitle' => 'Robin Song | ' . $category->name,
        'ogDescription' => $category->description
    ])
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="breadcrumbs-section">
        @include('front.partials.breadcrumbs', ['breadcrumbList' => \App\Services\Breadcrumbs::makeFor($category)])
    </div>
    <div class="w-section">
        <h1 class="h2">{{ $category->name }}</h1>
    </div>
    <div class="w-container filter-tab-container">
        <ul class="w-list-unstyled filter-tab-ul">
            <li id="all-tag" class="filter-tab-list-item active" data-group="all">ALL</li>
            @foreach($tags as $tag)
                <li class="filter-tab-list-item" data-group="{{ $tag }}">{{ $tag }}</li>
            @endforeach
        </ul>
    </div>
    <div class="w-section product-type-section">
        <div class="w-container product-type-container">
            <div class="w-row product-type-row" id="product-grid">
                @foreach($products as $product)
                    <div class="w-col w-col-4 product-type-column product-item-grid-box"
                         data-groups='["all"@foreach($product->getTagsList() as $tag),"{{ $tag }}" @endforeach]'>
                        <div class="product-type-wrapper rs-product-item">
                            <a href="/product/{{ $product->slug }}"><img src="{{ $product->coverPic('thumb') }}"
                                                                         class="product-type-image"></a>
                            <h4 class="product-item-heading">{{ $product->name }}</h4>
                            {{--<p class="p1">{{ $product->description }}</p>--}}
                            <div class="product-item-price">&pound;{{ $product->priceInPounds() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="/collections/{{ $category->collection->slug }}">
                <div class="purchase-btn narrow">Back to {{ $category->collection->name }}</div>
            </a>
        </div>
    </div>
    @include('front.partials.footer')
@endsection

@section('bodyscripts')
    <script>
        $(document).ready(function () {

            /* initialize shuffle plugin */
            var $grid = $('#product-grid');

            $grid.shuffle({
                itemSelector: '.product-item-grid-box' // the selector for the items in the grid
            });

            $('.filter-tab-list-item').click(function (e) {
                e.preventDefault();

                // set active class
                $('.filter-tab-list-item').removeClass('active');
                $(this).addClass('active');

                // get group name from clicked item
                var groupName = $(this).attr('data-group');

                // reshuffle grid
                $grid.shuffle('shuffle', groupName);
            });

            $(window).bind("load", function() {
                $('#all-tag').click();
            });


        });
    </script>
@endsection