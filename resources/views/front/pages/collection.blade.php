@extends('front.base')

@section('head')
    @include('front.partials.ogmeta', [
        'ogImage' => '/images/assets/logo_robinsong.png',
        'ogTitle' => 'Robin Song | ' . $collection->name,
        'ogDescription' => $collection->description
    ])
@endsection


@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="breadcrumbs-section">
        @include('front.partials.breadcrumbs', ['breadcrumbList' => \App\Services\Breadcrumbs::makeFor($collection)])
    </div>
    <div class="w-section">
        <h1 class="h2 collections">{{ $collection->name }}</h1>
    </div>
    <div class="w-section product-type-section">
        <div class="w-container product-type-container">
            <div class="w-row product-type-row">
                @foreach($categories as $category)
                    <a href="/categories/{{ $category->slug }}">
                        <div class="w-col w-col-6 product-type-column">
                            <div class="product-type-wrapper">
                                <img src="{{ $category->coverPic('thumb') }}"
                                     class="product-type-image"
                                     alt="{{ $category->name }} image"
                                >
                                <h1 class="h2 product-type-heading">{{ $category->name }}</h1>
                                <div class="p1">{{ $category->description }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <a href="/collections">
            <div class="purchase-btn narrow">Back to Shop</div>
        </a>
    </div>
    @include('front.partials.footer')
@endsection