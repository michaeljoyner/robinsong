@extends('front.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
    @include('front.partials.ogmeta', [
        'ogImage' => '/images/assets/logo_robinsong.png',
        'ogTitle' => 'Robin Song | Contact Us',
        'ogDescription' => 'Got a question? Or maybe you just have something to say? Good or bad, we are always open to suggestion and feedback, so please don\'t hesitate to send us a message'
    ])
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')

    <section class="contact-section sans-breadcrumb-heading">
        <h1 class="h2">CONTACT US</h1>
        <p class="contact-page-blurb">Please feel free to get in touch with any questions you may have.</p>
        @include('front.partials.contactform')
    </section>
    @include('front.partials.footer')
@endsection