@extends('front.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
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
                <p class="product-description p1">{{ $product->description }}</p>
                <div class="price-and-button-box">
                    <span class="product-price">&pound;{{ $product->price / 100 }}</span>
                    <button class="purchase-button btn" v-on:click="addToCart">Add to basket</button>
                </div>

                <div class="customize">
                    <h3 class="customize-heading">Customise your purchase</h3>
                    <div class="product-options">
                        @foreach($product->options as $index => $option)
                            <div class="product-option-select-box">
                                <label>{{ $option->name }}: </label>
                                <div class="select-container">
                                    <span class="select-arrow"></span>
                                    <select name="" v-model="choice{{ $index + 1 }}">
                                        <option value="">Select an option</option>
                                        @foreach($option->values as $value)
                                            <option value="{{ $option->name }}::{{ $value->name }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @foreach($product->customisations as $index => $customisation)
                        <label for="product_cusomisations[{{ $customisation->name }}]">{{ $customisation->name }}</label>
                        @if($customisation->longform)
                            <input type="hidden" v-model="text{{ $index + 1 }}['name']"
                                   value="{{ $customisation->name }}">
                            <textarea class="product-custom-text-input"
                                      name="product_customisations[{{ $customisation->name }}]"
                                      v-model="text{{ $index + 1 }}['value']"></textarea>
                        @else
                            <input type="hidden" v-model="text{{ $index + 1 }}['name']"
                                   value="{{ $customisation->name }}">
                            <input class="product-custom-text-input" type="text" name=""
                                   v-model="text{{ $index + 1 }}['value']">
                        @endif
                    @endforeach

                    <div class="quantity-input-box">
                        <label>Quantity: </label>
                        <input class="product-custom-text-input" type="number" v-model="quantity" number>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('front.partials.footer')
@endsection

@section('bodyscripts')

    <script>
        var productVue = new Vue({
            el: '#product-purchase-app',

            data: {
                productid: null,
                incart: false,
                quantity: 1,
                choice1: '',
                choice2: '',
                choice3: '',
                choice4: '',
                choice5: '',
                choice6: '',
                choice7: '',
                choice8: '',
                choice9: '',
                choice10: '',
                text1: {name: '', value: ''},
                text2: {name: '', value: ''},
                text3: {name: '', value: ''},
                text4: {name: '', value: ''},
                text5: {name: '', value: ''},
                text6: {name: '', value: ''},
                text7: {name: '', value: ''},
                text8: {name: '', value: ''},
                text9: {name: '', value: ''},
                text10: {name: '', value: ''},
            },

            ready: function () {
                this.$set('productid', $('.product-show-section').data('productid'));
            },

            methods: {

                addToCart: function () {
                    this.$http.post('/api/cart?q=' + Math.random(), {
                        id: this.productid,
                        options: this.prepareOptionsForPost(),
                        quantity: this.quantity,
                    }, function (res) {
                        this.$set('incart', true);
                        rsApp.basket.fetchInfo(true);
                    });
                },

                prepareOptionsForPost: function () {
                    var optionsArray = [], textArray = [];
                    var i = 0, j = 0;
                    for (i; i < 10; i++) {
                        if (this['choice' + (i + 1)] !== '') {
                            var input = this['choice' + (i + 1)].split('::');
                            var valueObj = {};
                            valueObj[input[0]] = input[1];
                            optionsArray.push(valueObj);
                        }
                    }
                    for (j; j < 10; j++) {
                        if (this['text' + (1 + j)].value !== '') {
                            var textinput = this['text' + (1 + j)];
                            var textObj = {};
                            textObj[textinput.name] = textinput.value;
                            textArray.push(textObj);
                        }
                    }
                    return {
                        options: optionsArray,
                        customisations: textArray
                    };
                }
            }
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