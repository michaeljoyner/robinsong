<style></style>

<template>
    <div class="stock-unit" :class="{'deleting': deleting, 'deleted': deleted}">
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
            <button v-on:click="deleteItem" class="delete-btn">
                <svg fill="#FF0000" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0V0z" fill="none"/>
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
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
                saving: false,
                deleting: false,
                deleted: false
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
            },

            deleteItem: function() {
                this.deleting = true;
                this.$http.delete('/admin/stockunits/' + this.unitId, function() {
                    this.$dispatch('remove-stockunit', {unitId: this.unitId});
                    this.deleted = true;
                }).error(function(res) {
                    console.log('error deleting item');
                    this.deleting = false;
                })
            }
        }
    }
</script>