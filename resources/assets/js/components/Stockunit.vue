<style></style>

<template>
    <div class="stock-unit">
        <input class="name-input" type="text" v-model="name" :disabled="!editing">
        <input class="price-input" type="text" v-model="price" :disabled="!editing">
        <input class="weight-input" type="text" v-model="weight" :disabled="!editing">
        <div class="toggle-btns">
            <button class="btn rs-btn btn-light btn-small" v-show="! editing" v-on:click="allowEdit">edit</button>
            <button class="btn rs-btn btn-small" v-show="editing" v-on:click="saveInput">
                <span v-show="!saving">Save</span>
                <div class="spinner" v-show="saving">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </button>
        </div>
        <div class="switch-box">
            <toggle-switch :identifier="unitId"
                           true-label="yes"
                           false-label="no"
                           :initial-state="available"
                           :toggle-url="'/admin/stockunits/' + unitId + '/availability'"
                           toggle-attribute="available"
            ></toggle-switch>
        </div>

    </div>
</template>

<script>
    module.exports = {
        props: ['name', 'weight', 'price', 'available', 'unit-id'],

        data: function() {
            return {
                editing: false,
                saving: false
            }
        },

        methods: {

            allowEdit: function() {
                this.editing = true;
            },

            saveInput: function() {
                this.saving = true;
                this.$http.post('/admin/stockunits/' + this.unitId, {
                    name: this.name,
                    price: this.price,
                    weight: this.weight
                }, function(res) {
                    this.name = res.name;
                    this.price = res.price;
                    this.weight = res.weight;
                    this.editing = false;
                    this.saving = false;
                }).error(function(res) {
                    console.log(res);
                    this.saving = false;
                })
            }
        }
    }
</script>