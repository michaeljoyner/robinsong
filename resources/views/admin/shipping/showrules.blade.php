@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@endsection

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">Shipping Rules</h1>

        <div class="rs-header-actions pull-right">
            <input type="text" class="add-location-input" v-model="new_location" placeholder="Add a new shipping area">
            <button class="btn rs-btn" v-on:click="addLocation">Add</button>
        </div>
        <hr>
    </div>
    <section class="rules-section">
        <div v-for="location in locations">
            <div class="shipping-location-container">
                <button class="btn rs-btn btn-clear-danger location-delete-btn" v-on:click="removeLocation(location)">Delete</button>
                <shipping-location :location-name="location.name" :location-id="location.id"></shipping-location>
            </div>
        </div>
    </section>
@endsection

@section('bodyscripts')
    <script>
        new Vue({
            el: 'body',

            data: {
                locations: [],
                new_location: ''
            },

            ready: function () {
                this.fetchLocations();
            },

            methods: {
                fetchLocations: function () {
                    this.$http.get('/admin/shipping/locations', function (res) {
                        this.$set('locations', res);
                    });
                },

                removeLocation: function(location) {
                    this.$http.delete('/admin/shipping/locations/' + location.id, function() {
                        this.locations.$remove(location);
                    });
                },

                addLocation: function() {

                    this.$http.post('/admin/shipping/locations', {name: this.new_location}, function(res) {
                        this.locations.push(res);
                        this.new_location = '';
                    });
                }
            }
        });
    </script>
@endsection