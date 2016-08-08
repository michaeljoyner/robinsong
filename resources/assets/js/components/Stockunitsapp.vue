<style></style>

<template>
    <div class="stockunit-app">
        <div class="stock-unit-column-headings">
            <span class="name-heading">Name</span>
            <span class="price-heading">Price (&pound;)</span>
            <span class="weight-heading">Weight (g)</span>
            <span class="button-heading">Actions</span>
            <span class="toggle-heading">Available</span>
        </div>
        <stock-unit v-for="unit in units"
                    :unit-id="unit.id"
                    :price="unit.price"
                    :weight="unit.weight"
                    :name="unit.name"
                    :available="unit.available"
        ></stock-unit>
    </div>
</template>

<script>
    module.exports = {
        props: ['product-id'],

        data: function() {
            return {
                units: []
            }
        },

        ready: function() {
            this.getStockUnits();
        },

        methods: {

            getStockUnits: function() {
                this.$http.get('/admin/products/' + this.productId + '/stockunits', function(res) {
                    this.$set('units', res);
                }).error(function(res) {
                    console.log(res);
                });
            }
        }
    }
</script>