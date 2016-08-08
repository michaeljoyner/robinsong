<style></style>

<template>
    <div class="product-purchasable">
        <div class="add-to-cart">
            <input type="number" v-model="quantity" class="product-quantity-box" min="1">
            <div class="select-container">
                <span class="select-arrow"></span>
                <select name="" id="" v-model="selected_unit" class="stock-unit-select">
                    <option value="">Select a package</option>
                    <option v-for="unit in stockunits" :value="unit.id">{{ unit.name }}</option>
                </select>
            </div>
            <button v-on:click="addToCart" class="btn purchase-btn inline-btn product-purchase" :disabled="selected_unit == ''">
                <span v-show="!waiting">Add to Cart</span>
                <div class="spinner" v-show="waiting">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </button>
        </div>
        <div class="customisations-and-options customize">
            <div class="product-options">
                <div v-for="option in productOptions" class="product-option-select-box">
                    <label>{{ option.name }}: </label>
                    <div class="select-container">
                        <span class="select-arrow"></span>
                        <select name="" v-model="options[option.name]">
                            <option value="">Select an option</option>
                            <option v-for="value in option.values" value="{{ value.name }}">{{ value.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="product-customisations">
                <div class="customisation" v-for="customisation in productCustomisations">
                    <label for="">{{ customisation.name }}</label>
                    <input class="product-custom-text-input" v-if="! customisation.longform" type="text" v-model="customisations[customisation.name]">
                    <textarea class="product-custom-text-input" v-if="customisation.longform" v-model="customisations[customisation.name]"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: ['product-id'],

        data: function() {
            return {
                waiting: false,
                quantity: 1,
                stockunits: [],
                selected_unit: '',
                productOptions: [],
                selectedOptions: {},
                setCustomisations: {},
                productCustomisations: [],
                customisations: {},
                options: {}
            }
        },

        ready: function() {
            this.fetchProductPurchaseOptions();
        },

        methods: {
            addToCart: function() {
                this.waiting = true;
                this.$http.post('/api/cart', {
                    unit_id: this.selected_unit,
                    quantity: this.quantity,
                    options: {
                        options: this.packIntoArray(this.options),
                        customisations: this.packIntoArray(this.customisations)
                    }
                }, function(res) {
                    rsApp.basket.fetchInfo();
                    this.waiting = false;
                }).error(function(res) {
                    this.waiting = false;
                });
            },

            packIntoArray: function(collection) {
                var choices = [], valueObj;
                for (var key in collection) {
                    if (collection.hasOwnProperty(key)) {
                        valueObj = {};
                        valueObj[key] = collection[key];
                        choices.push(valueObj)
                    }
                }
                return choices;
            },

            fetchProductPurchaseOptions: function() {
                this.$http.get('/api/products/' + this.productId + '/purchasing', function(res) {
                    var customisations = res.productCustomisations.reduce(function(acc, cust) {
                        acc[cust.name] = '';
                        return acc;
                    }, {});
                    var options = res.productOptions.reduce(function(acc2, opt) {
                        acc2[opt.name] = '';
                        return acc2;
                    }, {});

                    this.$set('stockunits', res.stockUnits);
                    this.$set('productOptions', res.productOptions);
                    this.$set('productCustomisations', res.productCustomisations);
                    this.$set('customisations', customisations);
                    this.$set('options', options);
                }).error(function(res) {});
            }
        }
    }
</script>