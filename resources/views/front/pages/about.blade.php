@extends('front.base')

@section('head')
    @include('front.partials.ogmeta', [
        'ogImage' => '/images/assets/logo_robinsong.png',
        'ogTitle' => 'Robin Song Occasions',
        'ogDescription' => 'About Robin Song, our story and some frequently asked questions.'
    ])
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="w-section sans-breadcrumb-heading">
        <h1 class="h2 collections">About Robin Song Occasions</h1>
    </div>
    <div class="w-section about-us-section">
        <section class="user-text">
            {!! $page->textFor('about us') !!}
        </section>
    </div>
    <div class="faqs-section" id="faqs">
        <h1 class="h2 collections">Frequently Asked Questions</h1>
        <section class="user-text">
            {!! $page->textFor('faqs') !!}
        </section>
    </div>
    @include('front.partials.footer')
@endsection