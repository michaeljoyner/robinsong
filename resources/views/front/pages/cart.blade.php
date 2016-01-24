@extends('front.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@endsection

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="breadcrumbs-section">
        <div class="breadcrumbs-text p1">COLLECTIONS &gt; WEDDINGS</div>
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
    <template id="cart-list-item-template">
        <div class="cart-list-item">
            <div class="item-container item-image-container">
                <img v-bind:src="thumbnail" alt="image thumbnail">
            </div>
            <div class="item-container item-name-container">
                <h3 class="item-name">@{{ itemname }}</h3>
                <span v-if="hasCustomisations" v-on:click="showModal = true"><img src="/images/assets/info_icon.png"
                                                                                  alt="tap to review your info" width="20px" height="20px"></span>
                <modal :show.sync="showModal">
                    <h3 slot="header">Your Choices and Customisations</h3>
                    <div slot="body">
                        <h4>Choices</h4>
                        <p v-for="option in options.options">@{{ this.showOptions(option) }}</p>
                        <h4>Customisations</h4>
                        <p v-for="customisation in options.customisations">@{{ this.showCustomisations(customisation) }}</p>
                    </div>
                </modal>
            </div>
            <div class="item-container item-details-container">

            </div>
            <div class="item-container item-qty-container">
                <input type="number" v-model="quantity" v-if="canEdit" class="item-quantity">
                <p v-else class="item-quantity">@{{ quantity }}</p>
                <button v-on:click="handleEditButtonClick">
                    @{{ buttonTxt }}
                </button>
            </div>
            <div class="item-container item-price-container">
                <p class="item-price">&pound;@{{ (price * quantity) / 100}}</p>
            </div>
        </div>
    </template>
    <template id="modal-template">
        <div class="modal-mask" v-show="show" transition="modal">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="modal-header">
                        <slot name="header">
                            default header
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                            default body
                        </slot>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="modal-default-button"
                            @click="show = false">
                            OK
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@section('bodyscripts')
    <script>
        Vue.component('modal', {
            template: '#modal-template',
            props: {
                show: {
                    type: Boolean,
                    required: true,
                    twoWay: true
                }
            }
        });

        Vue.component('cart-list-item', {

            props: ['itemname', 'quantity', 'price', 'rowid', 'thumbnail', 'options'],

            template: '#cart-list-item-template',

            data: function() {
                return {
                    editing: false,
                    waiting: false,
                    showModal: false
                }
            },

            computed: {
                buttonTxt: function() {
                    if(this.waiting) {
                        return 'wait';
                    }
                    return this.editing ? 'Save' : 'Edit';
                },

                canEdit: function() {
                    return this.editing && (! this.waiting)
                },

                hasCustomisations: function() {
                    return (this.options.options.length > 0) || (this.options.customisations.length > 0);
                }
            },

            methods: {
                handleEditButtonClick: function() {
                    if(this.editing) {
                        this.updateQuantity();
                    } else {
                        this.editing = true;
                    }
                },

                updateQuantity: function() {
                    this.waiting = true;
                    this.$http.post('/api/cart/' + this.rowid, {qty: this.quantity}, function(res) {
                        this.editing = false;
                        this.waiting = false;
                        this.$dispatch('quantity.updated');
                    });
                },

                showOptions: function(option) {
                    var res = [];
                    for(var key in option)  {
                        if(option.hasOwnProperty(key)) {
                            res.push(key);
                        }
                    }

                    return res[0] + ': ' + option[res[0]];
                },

                showCustomisations: function(customisation) {
                    var res = [];
                    for(var key in customisation)  {
                        if(customisation.hasOwnProperty(key)) {
                            res.push(key);
                        }
                    }

                    return res[0] + ': ' + customisation[res[0]];
                }
            }
        });

        new Vue({
            el: '#cart-list',

            data: {
                items: [],
                shipping: [],
            },

            computed: {
                total: function() {
                    return this.items.reduce(function(sum, item) {
                        return sum + (item.price * item.qty);
                    }, 0);
                }
            },

            ready: function() {
                this.fetchItems();
                this.fetchShippingPrices();
            },

            events: {
                'quantity.updated': function() {
                    this.fetchShippingPrices();
                }
            },

            methods: {
                fetchItems: function() {
                    this.$http.get('/api/cart', function(res) {
                        this.$set('items', res);
                    });
                },

                fetchShippingPrices: function() {
                    this.$http.get('/api/cart/shipping', function(res) {
                        this.$set('shipping', res);
                    });
                },

                removeItem: function(item) {
                    this.$http.delete('/api/cart/' + item.rowid, {}, function(res) {
                        this.items.$remove(item);
                        this.fetchShippingPrices();
                    });
                }
            }
        });
    </script>
@endsection