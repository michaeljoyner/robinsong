@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $product->name }}</h1>

        <div class="rs-header-actions pull-right">
            <a href="/admin/products/{{ $product->id }}/edit">
                <div class="btn rs-btn">Edit</div>
            </a>
        </div>
        <hr>
    </div>
    <div class="product-summary-section row">
        <div class="col-md-6 product-details">
            <p class="lead">{{ $product->description }}</p>

            <p class="lead product-price"><strong>Price: </strong>&pound;{{ $product->price }}</p>
            <p class="lead product-price"><strong>Weight: </strong>{{ $product->weight }}g</p>

            <div id="product-options-vue" class="product-options-vue">
                <h4 class="product-options-heading">Product Options</h4>

                <p>These are options a customer may select through a dropdown selection</p>
                <ul class="product-options-list list-group">
                    <li class="list-group-item" v-for="option in options">
                        <span v-on:click="removeOption(option)" class="close-icon-btn">&times;</span>
                        <product-option :optionname="option.name" :optionid="option.id"></product-option>
                    </li>
                </ul>
                <p class="empty-list-state" v-show="options.length === 0">The are no options for this product.</p>

                <div class="add-option-form">
                    <input type="text" v-model="newoption" placeholder="Add new option">
                    <button class="btn rs-btn btn-light"
                            v-on:click="addOption"
                    >Add
                    </button>
                </div>
            </div>
            <div id="product-customisations-vue" class="product-customisations-vue">
                <h4 class="product-customisations-heading">Custom Text Fields</h4>

                <p>These allow a customer to enter text to be used in their order. The text fields can either be normal
                    text inputs (default) or a textarea for longer input.</p>

                <p class="empty-list-state" v-show="customisations.length === 0">
                    The are no custom fields for this product.
                </p>
                <ul class="list-group customisations-list">
                    <li class="list-group-item" v-for="customisation in customisations">
                        <span v-on:click="removeCustomisation(customisation)" class="close-icon-btn">&times;</span>
                        <product-customisation :cname="customisation.name"
                                               :cid="customisation.id"></product-customisation>
                        <span class="badge">@{{ customisation.longform ? 'L' : 'S' }}</span>
                    </li>
                </ul>
                <div class="add-customisation-form">
                    <input type="text" v-model="newcustomisation.name" placeholder="Add a new custom text field">
                    <button class="btn rs-btn btn-light" v-on:click="addCustomisation">Add</button>
                    <label for="longform-checkbox">Needs textarea: </label>
                    <input id="longform-checkbox" type="checkbox" v-model="newcustomisation.longform">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="product-image-vue" class="single-image-uploader-box">
                <singleupload default="{{ $product->coverPic('thumb') }}"
                              url="/admin/uploads/products/{{ $product->id }}/cover"
                              shape="square"
                              size="large"
                ></singleupload>
            </div>
            <div id="gallery-app" class="product-gallery-container">
                <h4 class="gallery-heading">Product Gallery Images</h4>

                <gallery-show
                        gallery="{{ $gallery->id }}"
                        geturl="/admin/uploads/galleries/{{ $gallery->id }}/images"
                ></gallery-show>
                <dropzone
                        url="/admin/uploads/galleries/{{ $gallery->id }}/images"
                ></dropzone>
            </div>
        </div>
    </div>
    <template id="option-template">
        <div class="option-values-wrapper" v-bind:class="{'active': active}">
            <div class="inner-values-modal">
                <span class="option-name" v-on:click="active = ! active">@{{ optionname }}</span>

                <div class="value-list" v-show="active">
                    <div class="option-values-list-container">
                        <p class="empty-list-state" v-show="values.length === 0">Add values for customers to select
                            from. These will be the options they may select from the drop down menu.</p>
                        <ul class="list-group option-values-list">
                            <li class="list-group-item" v-for="value in values">
                                <span>@{{ value.name }}</span>
                                <span class="badge" v-on:click="removeValue(value)">&times;</span>
                            </li>
                        </ul>
                    </div>
                    <div class="add-value-box">
                        <input type="text" v-model="newvalue">
                        <button class="btn btn-light rs-btn" v-on:click="addValue">Add</button>
                    </div>
                    <div class="values-modal-footer">
                        <button class="btn rs-btn" v-on:click="active = ! active">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template id="customisation-template">
        <span>@{{ cname }}</span>
    </template>
@endsection

@section('bodyscripts')
    <script>
        Vue.config.debug = true;
        Vue.component('product-customisation', {

            props: ['cname', 'cid'],

            template: '#customisation-template',

            data: function () {

            }
        });

        Vue.component('product-option', {
            props: ['optionname', 'optionid'],

            template: '#option-template',

            data: function () {
                return {
                    values: [],
                    newvalue: '',
                    active: false
                }
            },

            ready: function () {
                this.fetchValues();
            },

            methods: {
                addValue: function () {
                    this.$http.post('/admin/productoptions/' + this.optionid + '/values', {'name': this.newvalue}, function (res) {
                        this.values.push(res);
                    }).error(function (res) {

                    });
                    this.newvalue = '';
                },

                fetchValues: function () {
                    this.$http.get('/admin/productoptions/' + this.optionid + '/values', function (res) {
                        this.$set('values', res);
                    });
                },

                removeValue: function (value) {
                    this.$http.delete('/admin/optionvalues/' + value.id, function (res) {
                        this.values.$remove(value);
                    });
                }
            }
        });
        new Vue({
            el: '#product-image-vue'
        });
        new Vue({
            el: '#product-options-vue',

            data: {
                options: [],
                newoption: '',
                product: {{ $product->id }}


            },

            ready: function () {
                this.fetchOptions();
            },

            methods: {
                addOption: function () {
                    this.$http.post('/admin/products/' + this.product + '/options', {'name': this.newoption}, function (res) {
                        this.options.push(res);
                    }).error(function (res) {
                        console.log(res);
                    });
                    this.newoption = '';
                },

                fetchOptions: function () {
                    this.$http.get('/admin/products/' + this.product + '/options', function (res) {
                        this.$set('options', res);
                    })
                },

                removeOption: function (option) {
                    this.$http.delete('/admin/productoptions/' + option.id, function (res) {
                        this.options.$remove(option);
                    });
                }
            }
        });

        new Vue({

            el: '#product-customisations-vue',

            data: {
                customisations: [],
                newcustomisation: {
                    name: '',
                    longform: false
                },
                product: {{ $product->id }}

            },

            ready: function () {
                this.fetchCustomisations();
            },

            methods: {
                fetchCustomisations: function () {
                    this.$http.get('/admin/products/' + this.product + '/customisations', function (res) {
                        this.$set('customisations', res);
                    });
                },

                addCustomisation: function () {
                    this.$http.post('/admin/products/' + this.product + '/customisations', {
                        'name': this.newcustomisation.name,
                        'longform': this.newcustomisation.longform
                    }, function (res) {
                        this.customisations.push(res);
                    });
                    this.newcustomisation.name = '';
                    this.newcustomisation.longform = false;
                },

                removeCustomisation: function (customisation) {
                    this.$http.delete('/admin/customisations/' + customisation.id, function (res) {
                        this.customisations.$remove(customisation);
                    });
                }
            }
        });

        var gallery = new Vue({
            el: '#gallery-app',

            events: {
                'image-added': function (image) {
                    this.$broadcast('add-image', image);
                }
            }
        });
    </script>
@endsection