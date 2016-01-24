<style>

</style>

<template>
    <div class="location-box">
        <header>
            <h2>{{ locationName }}</h2>
            <hr>
        </header>
        <div class="location-classes">
            <table class="table">
                <thead>
                <tr>
                    <td>Weight range</td>
                    <td>Shipping Price</td>
                    <td>Actions</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="limit in limits">
                    <td>{{ getRange($index) }}</td>
                    <td>&pound;{{ limit.price / 100}}</td>
                    <td><button class="btn rs-btn btn-tiny btn-clear-danger" v-on:click="removeLimit(limit)">delete</button></td>
                </tr>
                </tbody>
            </table>
        </div>
        <p class="add-weight-class-instruction">Add a new weight limit and price</p>
        <form class="add-class-form rs-form">
            <label for="weight-limit">Upper Weight Limit (grams): </label>
            <input type="number" id="weight-limit" v-model="new_limit">
            <label for="weight-price">Shipping Price (&pound;): </label>
            <input type="number" id="weight-price" v-model="new_price">
            <button class="btn rs-btn btn-light btn-tiny"
                    v-on:click.prevent="addLimit"
            >Add
            </button>
        </form>
        <p class="add-weight-class-instruction">You can have free shipping above a cretain amount</p>
        <div class="free-shipping-price-form">
            <div v-show="editForm">
                <p class="free-shipping-price-notice">Shipping is free for orders above
                    <span class="free-shipping-price">&pound;{{ free_shipping_above / 100 }}</span>
                </p>
                <button class="btn rs-btn btn-light btn-light btn-tiny"
                        v-on:click="showEditView"
                >edit</button>
                <button class="btn rs-btn btn-clear-danger btn-tiny"
                        v-on:click="removeFreeShippingPrice"
                >No free shipping</button>
            </div>
            <div v-else>
                <p>Make shipping free for orders above this price</p>
                <input type="number" v-model="new_free_shipping_price">
                <button class="btn rs-btn btn-light btn-tiny"
                        v-on:click="setFreeShippingPrice"
                >Set Price</button>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: ['location-name', 'location-id'],

        computed: {
            editForm: function() {
                return !(this.editing || (this.free_shipping_above == '' || this.free_shipping_above == null));
            }
        },

        data: function () {
            return {
                limits: [],
                new_limit: '',
                new_price: '',
                free_shipping_above: '',
                new_free_shipping_price: '',
                editing: false
            }
        },

        ready: function () {
            this.fetchLimits();
            this.fetchFreeShippingPrice();
        },

        methods: {
            fetchLimits: function () {
                console.log(this.locationId);
                this.$http.get('/admin/shipping/locations/' + this.locationId + '/weightclasses', function (res) {
                    this.$set('limits', res);
                });
            },

            fetchFreeShippingPrice: function() {
                this.$http.get('/admin/shipping/locations/' + this.locationId + '/getfreeprice', function(res) {
                    this.$set('free_shipping_above', res.free_shipping_above);
                });
            },

            getRange: function (index) {
                if (index === 0) {
                    return '0g - ' + this.limits[index].weight_limit + 'g';
                }

                return (parseInt(this.limits[index - 1].weight_limit) + 1) + 'g - ' + (this.limits[index].weight_limit) + 'g';
            },

            addLimit: function () {
                if(this.new_limit == "" || this.newPrice == "") {
                    return;
                }

                this.$http.post('/admin/shipping/locations/' + this.locationId + '/weightclasses', {
                    weight_limit: this.new_limit,
                    price: parseInt(this.new_price * 100)
                }, function (res) {
                    this.limits.push(res);
                    this.sortLimits();
                });
                this.new_limit = '';
                this.new_price = '';
            },

            removeLimit: function(limit) {
                this.$http.delete('/admin/shipping/weightclasses/'+limit.id, function(res) {
                    this.limits.$remove(limit);
                });
            },

            sortLimits: function () {
                var sortedLimits = this.limits.sort(function (a, b) {
                    if (a.weight_limit < b.weight_limit) {
                        return -1;
                    } else if (a.weight_limit > b.weight_limit) {
                        return 1;
                    }
                    return 0;
                });

                this.$set('limits', sortedLimits);
            },

            showEditView: function() {
                this.editing = true;
            },

            setFreeShippingPrice: function() {
                if(this.new_shipping_price == '') {
                    return;
                }

                if(this.new_shipping_price === (this.free_shipping_above / 100)) {
                    this.editing = false;
                    return;
                }
                this.$http.post('/admin/shipping/locations/' + this.locationId + '/setfreeprice', {
                    free_shipping_above: (this.new_free_shipping_price * 100)
                }, function(res) {
                    this.$set('free_shipping_above', res.free_shipping_above );
                    this.editing = false;
                });
            },

            removeFreeShippingPrice: function() {
                this.$http.delete('/admin/shipping/locations/' + this.locationId + '/removefreeprice', function(res) {
                    if(res.success) {
                        this.$set('free_shipping_above', '');
                        console.log(this.free_shipping_above);
                    }
                })
            }
        }
    }
</script>