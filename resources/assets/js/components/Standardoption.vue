<style></style>

<template>
    <div class="standard-option">
        <header class="clearfix">
            <h1 class="pull-left">{{ optionName }}</h1>
            <form class="input-and-go-form pull-right" v-on:submit.stop.prevent="addValue">
                <input type="text" placeholder="Value name" v-model="newName">
                <button v-on:click.stop.prevent="addValue" type="button" class="btn rs-btn btn-clear">
                    Create Option
                </button>
            </form>
        </header>
        <div class="standard-option-values">
            <ul>
                <li v-for="value in values | orderBy 'name'">
                    <span>{{ value.name }}</span>
                    <span class="close-button" v-on:click="removeValue(value)">&times;</span>
                </li>
            </ul>
        </div>
        <footer class="clearfix">
            <div class="actions pull-right">
                <button v-on:click.stop.prevent="removeOption" type="button" class="btn rs-btn btn-clear-danger">
                    Delete
                </button>
            </div>
        </footer>
    </div>
</template>

<script>
    module.exports = {
        props: ['option-id', 'option-name'],

        data: function () {
            return {
                values: [],
                newName: ''
            }
        },

        ready: function () {
            this.getValues();
        },

        methods: {
            getValues: function () {
                this.$http.get('/admin/standard-options/' + this.optionId + '/values', function (res) {
                    this.$set('values', res);
                }).error(function (res) {
                    console.log(res);
                });
            },

            addValue: function () {
                if (this.newName === '') return;

                this.$http.post('/admin/standard-options/' + this.optionId + '/values', {name: this.newName}, function (res) {
                    this.values.push(res);
                    this.newName = '';
                }).error(function (res) {
                    console.log(res);
                });
            },

            removeValue: function (value) {
                this.$http.delete('/admin/standard-option-values/' + value.id, function (res) {
                    this.values.$remove(value);
                }).error(function (res) {
                    console.log(res);
                })
            },

            removeOption: function () {
                this.$dispatch('remove-option', this.optionId);
            }
        }
    }
</script>