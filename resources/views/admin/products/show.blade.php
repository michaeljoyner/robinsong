@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">{{ $product->name }}</h1>

        <div id="toggle-available-app" class="rs-header-actions pull-right">
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

@section('bodyscripts')
    <script>
        Vue.config.debug = true;

        new Vue(app.vueConstructorObjects.productImageVue);
        new Vue(app.vueConstructorObjects.productOptionsVue);
        new Vue(app.vueConstructorObjects.productCustomisationVue);
        new Vue(app.vueConstructorObjects.tagApp);
        new Vue(app.vueConstructorObjects.galleryApp);
        new Vue(app.vueConstructorObjects.toggleBtnVue);

    </script>
@endsection