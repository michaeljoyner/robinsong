@extends('front.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
    @include('front.partials.ogmeta', [
        'ogImage' => '/images/assets/logo_robinsong.png',
        'ogTitle' => 'Robin Song | ' . $product->name,
        'ogDescription' => $product->description
    ])
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="breadcrumbs-section">
        @include('front.partials.breadcrumbs', ['breadcrumbList' => \App\Services\Breadcrumbs::makeFor($product)])
    </div>
    <section class="product-show-section w-container wider" data-productid="{{ $product->id }}">
        <div class="w-row">
            <div id="product-gallery-app" class="picture-view-col w-col w-col-5"
                 data-gallery-id="{{ $product->getGallery()->id }}">
                <div class="main-product-image-container">
                    <img class="main-product-image"
                         v-bind:src="displaysrc"
                         v-on:mouseover="showMagnifier"
                         v-on:mouseout="hideMagnifier"
                         v-on:mousemove="magnify"
                         data-original-src="{{ $product->coverPic('web') }}"
                         data-original-large="{{ $product->coverPic() }}"
                         alt="{{ $product->name }} image"
                    >
                    <canvas id="magnifier"
                            width="80" height="80"
                            v-show="magnified"
                    ></canvas>
                </div>
                <div class="thumbnail-container">
                    <img src="{{ $product->coverPic('thumb') }}" alt="product image" v-on:click="reset">
                    <img v-for="image in images"
                         :src="image.thumb_src"
                         v-on:click="show(image)"
                         alt="product image"
                    >
                </div>
            </div>
            <div id="product-purchase-app" class="product-detail-col w-col w-col-7">
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-description p1">
                    @if($product->hasWriteup())
                    {!! $product->writeup !!}
                    @else
                    {!! nl2br($product->description) !!}
                    @endif
                </div>
                <div class="product-pricing">
                    @foreach($product->stockUnits as $unit)
                        @if($unit->available)
                        <div class="stock-unit-price">
                            <span class="unit-price product-price">&pound; {{ $unit->price->asCurrencyString() }}</span>
                            <span class="unit-name">{{ $unit->name }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                <purchasable product-id="{{ $product->id }}"></purchasable>
            </div>
        </div>
    </section>
    @include('front.partials.footer')
@endsection

@section('bodyscripts')

    <script>
        var productVue = new Vue({
            el: '#product-purchase-app',
        });

        var gal = new Vue({
            el: '#product-gallery-app',

            data: {
                images: [],
                galleryid: '',
                original: '',
                current: '',
                magnified: false,
                large: '',
                original_large: '',
                magnifier_size: 120
            },

            computed: {
              displaysrc: function() {
                  if(this.current !== '' && this.current != null) {
                      return this.current.web_src;
                  }

                  return this.original;
              }
            },

            ready: function () {
                this.$set('galleryid', $('#product-gallery-app').data('gallery-id'));
                this.$set('original', $('.main-product-image').data('original-src'));
                this.$set('original_large', $('.main-product-image').data('original-large'));
                this.large = new Image();
                this.setLarge(this.original_large);
                this.fetchImages();
            },

            methods: {
                fetchImages: function () {
                    this.$http.get('/admin/uploads/galleries/' + this.galleryid + '/images', function (res) {
                        this.$set('images', res);
                    });
                },

                setLarge: function(source) {
                    this.large.src = source;
                },

                show: function(image) {
                    this.$set('current', image);
                    this.setLarge(image.src);
                },

                reset: function() {
                    this.$set('current', null);
                    this.setLarge(this.original_large);
                },

                magnify: function(ev) {
                    var magnifier = document.querySelector('#magnifier');
                    magnifier.width = this.magnifier_size;
                    magnifier.height = this.magnifier_size;
                    var ctx = magnifier.getContext('2d');
                    var imageWidth = ev.target.width;
                    var imageHeight = ev.target.height;
                    var origX = ((ev.offsetX / imageWidth) * this.large.width) - (this.magnifier_size / 4);
                    var origY = ((ev.offsetY / imageHeight) * this.large.height) - (this.magnifier_size / 4);
                    ctx.drawImage(this.large, origX, origY, (this.magnifier_size / 2), (this.magnifier_size / 2), 0, 0, this.magnifier_size, this.magnifier_size);
                    magnifier.style.top = (ev.offsetY + 20) + 'px';
                    magnifier.style.left = (ev.offsetX + 20) + 'px';
                },

                showMagnifier: function() {
                    this.magnified = true;
                },

                hideMagnifier: function() {
                    this.magnified = false;
                }
            }
        });
    </script>

@endsection