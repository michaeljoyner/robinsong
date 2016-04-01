@extends('front.base')

@section('head')
    @include('front.partials.ogmeta', [
        'ogImage' => '/images/assets/logo_robinsong.png',
        'ogTitle' => 'Robin Song Collections',
        'ogDescription' => 'Browse through our collections of wonderfully hand-crafted goods, from wedding reception guest books to unique photo frames'
    ])
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="w-section sans-breadcrumb-heading">
        <h1 class="h2 collections">Our Collections</h1>
    </div>
    <div class="w-section product-type-section">
        <div class="w-container product-type-container">
            <div class="w-row product-type-row">
                @foreach($collections as $collection)
                <div class="w-col w-col-6 product-type-column">
                    <a href="/collections/{{ $collection->slug }}">
                        <div class="product-type-wrapper">
                            <img src="{{ $collection->coverPic('thumb') }}" class="product-type-image">
                            <h1 class="h2 product-type-heading">{{ $collection->name }}</h1>
                            <div class="p1">{{ $collection->description }}</div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
   @include('front.partials.footer')
@endsection