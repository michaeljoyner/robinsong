@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $product->name }}</h1>

        <div id="toggle-available-app" class="rs-header-actions pull-right">
            <button type="button" class="btn rs-btn btn-clear" data-toggle="modal" data-target="#writeup-modal">
                Write up
            </button>
            <toggle-button url="/admin/api/products/{{ $product->id }}/availability"
                           initial="{{ $product->available ? 1 : 0 }}"
                           toggleprop="available"
                           onclass=""
                           offclass="btn-danger"
                           offtext="Make Available"
                           ontext="Mark as Unavailable"></toggle-button>
            <a href="/admin/products/{{ $product->id }}/edit">
                <div class="btn rs-btn">Edit</div>
            </a>
            @include('admin.partials.deletebutton', [
                'objectName' => $product->name,
                'deleteFormAction' => '/admin/products/'.$product->id
            ])
        </div>
        <hr>
    </div>
    <div class="product-summary-section row">
        <div class="col-md-6 product-details">
            <p class="lead">{{ $product->description }}</p>
            <p class="lead product-price"><strong>Price: </strong>&pound;{{ $product->priceInPounds() }}</p>
            <p class="lead product-price"><strong>Weight: </strong>{{ $product->weight }}g</p>
            <div id="tag-app">
                <tag-manager productid="{{ $product->id }}"
                             taglist="{{ implode(',' , $product->getTagsList()) }}"></tag-manager>
            </div>


            <div id="product-options-vue" class="product-options-vue" data-product="{{ $product->id }}">
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
                <div class="standard-option-choices">
                    <h4>Standard Option Choices</h4>
                    <div class="small-instruction">Click on one of the choices below to add a pre-defined option</div>
                    <div class="standard-option-choice-button"
                         v-on:click="addStandardOptionToProduct(standardOption)"
                         v-for="standardOption in standardOptions | orderBy 'name'"
                    >
                        @{{ standardOption.name }}
                    </div>
                </div>
            </div>
            <div id="product-customisations-vue" class="product-customisations-vue" data-product="{{ $product->id }}">
                <h4 class="product-customisations-heading">Custom Text Fields</h4>

                <p>These allow a customer to enter text to be used in their order. The text fields can either be normal
                    text inputs (default) or a textarea for longer input.</p>

                <p class="empty-list-state" v-show="customisations.length === 0">
                    The are no custom fields for this product.
                </p>
                <ul class="list-group customisations-list">
                    <li class="list-group-item" v-for="customisation in customisations">
                        <span v-on:click="removeCustomisation(customisation)" class="close-icon-btn">&times;</span>
                        <span>@{{ customisation.name }}</span>
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
@endsection
<div class="modal fade" id="writeup-modal" data-productid="{{ $product->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Product Writeup</h4>
            </div>
            <div class="modal-body">
                <textarea v-model="writeup" name="writeup" id="writeup-body">{!! $product->writeup !!}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn rs-btn btn-clear-danger" data-dismiss="modal">Cancel</button>
                <button v-on:click="saveWriteup" class="btn rs-btn btn-light">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@include('admin.partials.deletemodal')

@section('bodyscripts')
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        Vue.config.debug = true;

        new Vue(app.vueConstructorObjects.productImageVue);
        new Vue(app.vueConstructorObjects.productOptionsVue);
        new Vue(app.vueConstructorObjects.productCustomisationVue);
        new Vue(app.vueConstructorObjects.tagApp);
        new Vue(app.vueConstructorObjects.galleryApp);
        new Vue(app.vueConstructorObjects.toggleBtnVue);

        new Vue({
            el: '#writeup-modal',

            data: {
                writeup: '',
                productId: null
            },

            ready: function() {
                var productId = document.querySelector('#writeup-modal').getAttribute('data-productid');
                this.$set('productId', productId);
            },

            methods: {
                saveWriteup: function() {
                    var content = tinymce.activeEditor.getContent();

                    this.$http.post('/admin/products/' + this.productId + '/writeup', {writeup: content}, function(res) {
                        $('#writeup-modal').modal('hide');
                    }).error(function(res) {
                        alert('Oops, failed to save. Please try later.');
                    });
                }
            }
        });

    </script>
    @include('admin.partials.modalscript')
    <script>
        tinymce.init({
            selector: '#writeup-body',
            plugins: ['link', 'image', 'paste'],
            menubar: false,
            toolbar: 'undo redo | styleselect | bold italic | bullist numlist | link mybutton',
            paste_data_images: true,
            height: 500,
            body_class: 'article-body-content',
            content_style: "body {font-size: 16px; max-width: 800px; margin: 0 auto; padding: 10px;} * {font-size: 16px;} img {max-width: 100%; height: auto;}",
        });
    </script>
@endsection