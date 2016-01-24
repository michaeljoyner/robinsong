@extends('front.base')

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.homeheader')
    @include('front.partials.navbar')
    <div class="home-intro-section w-section section w-container thank-you-section">
        <p class="p1 large-lead">Thank you for your purchase! Your order number is {{ Session::get('order_number') }}</p>
        <p class="p1">If you have any queries, please don't hesitate to get in touch.</p>
    </div>
    <div class="w-section section w-container super-wide thanks-banner thank-you-section">
        <p>Thank You!</p>
    </div>

    @include('front.partials.footer')
@endsection