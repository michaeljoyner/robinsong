@extends('front.base')

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.homeheader')
    @include('front.partials.navbar')
    <div class="w-section section slide-section">
        <div class="w-container wider slider-container">
            <div data-animation="slide" data-duration="1000" data-infinite="1" data-autoplay="1" data-delay="3000" class="w-slider slider">
                <div class="w-slider-mask slide-mask">
                    @foreach($sliderImages as $image)
                        <div class="w-slide slide">
                            <img src="{{ $image->getUrl('wide') }}" alt="image">
                        </div>
                    @endforeach
                </div>
                <div class="w-slider-arrow-left slide-arrow">
                    <div class="w-icon-slider-left slider-icon"></div>
                </div>
                <div class="w-slider-arrow-right slide-arrow">
                    <div class="w-icon-slider-right slider-icon"></div>
                </div>
                <div class="w-slider-nav w-round slider-dots"></div>
            </div>
        </div>
    </div>
    <div class="home-intro-section w-section section w-container">
        <p class="p1 large-lead">{{ $intro }}</p>
    </div>
    <div class="w-section section categories-section">
        <div class="w-container categories-container">
            <h1 class="h2">Hot Prods</h1>
        </div>
        <section class="product-grid-view">
            @foreach(range(1,4) as $index)
                @foreach($products->shuffle() as $product)
                    <div class="hot-product">
                        <a href="/product/{{ $product->slug }}"><img src="{{ $product->coverPic('thumb') }}" alt=""></a>
                        <div class="product-info">
                            <h5 class="product-title">{{ $product->name }}</h5>
                            <p class="price">&pound;{{ $product->priceInPounds() }}</p>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </section>
    </div>

    @include('front.partials.footer')
@endsection