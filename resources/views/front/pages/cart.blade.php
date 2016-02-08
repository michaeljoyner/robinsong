@extends('front.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
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
                <div class="cart-item-delete-btn" v-on:click="removeItem(item)">Remove</div>
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
                <div class="checkout-btn">Checkout</div>
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