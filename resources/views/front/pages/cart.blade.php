@extends('front.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="breadcrumbs-section">
        {{--<div class="breadcrumbs-text p1">COLLECTIONS &gt; WEDDINGS</div>--}}
    </div>
    <div class="w-section">
        <h1 class="h2 collections">Your Cart</h1>
    </div>
    <section id="cart-list" class="cart-list-section w-container">
        <ul class="cart-outer-list">
            <li v-for="item in items">
                <cart-list-item :price="item.price"
                                :rowid="item.rowid"
                                :quantity.sync="item.qty"
                                :thumbnail="item.options.thumbnail"
                                :options="item.options"
                                :itemname="item.name"
                ></cart-list-item>
                <div class="cart-item-delete-btn" v-on:click="removeItem(item)">
                    <svg fill="#6d6d6d" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    </svg>
                </div>
            </li>
        </ul>
        <h2 v-if="! items.length">Your basket is currently empty</h2>
        <section class="cart-summary">
            <p class="cart-total-price">Sub-Total: &pound;@{{ total/100 }}</p>
            <h4 class="shipping-price-heading">Shipping:</h4>
            <ul class="shipping-price-list">
                <li v-for="area in shipping">
                    <span class="shipping-area">@{{ area.name}}: </span>
                    <span class="shipping-price"> &pound;@{{ area.price/100 }}</span>
                </li>
            </ul>
        </section>
        <section class="to-checkout">
            <a href="/checkout">
                <div class="purchase-btn narrow">Checkout</div>
            </a>
        </section>
    </section>
    @include('front.partials.footer')
@endsection

@section('bodyscripts')
    <script>
        new Vue(rsApp.frontConstructorObjects.cartPageVue);
    </script>
@endsection