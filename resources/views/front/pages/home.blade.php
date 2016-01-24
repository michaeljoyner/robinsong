@extends('front.base')

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.homeheader')
    @include('front.partials.navbar')
    <div class="w-section section slide-section">
        <div class="w-container wider slider-container">
            <div data-animation="slide" data-duration="500" data-infinite="1" class="w-slider slider">
                <div class="w-slider-mask slide-mask">
                    <div class="w-slide slide slide-1"></div>
                    <div class="w-slide slide slide-2"></div>
                    <div class="w-slide slide slide-3"></div>
                    <div class="w-slide slide slide-4">
                        <img src="{{ asset('images/assets/marriage.jpg') }}" alt="image">
                    </div>
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
        <p class="p1 large-lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias commodi dignissimos dolorem dolorum error, esse ipsam ipsum minus neque nobis nulla perspiciatis placeat quaerat quia, ratione repellat sapiente soluta veniam?</p>
    </div>
    <div class="w-section section categories-section">
        <div class="w-container categories-container">
            <h1 class="h2">Collections</h1>
            <div class="w-row categories-row">
                @foreach($collections as $collection)
                    <div class="w-col w-col-6 categories-column">
                        <img src="{{ $collection->coverPic('web') }}" class="category-image">
                        <h1 class="h2 categories-heading">{{ $collection->name }}</h1>
                        <a href="/collections/{{ $collection->slug }}" class="w-button button">EXPLORE NOW</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('front.partials.footer')
@endsection