<style>

</style>

<template>
    <div class="option-values-wrapper" v-bind:class="{'active': active}">
        <div class="inner-values-modal">
            <span class="option-name" v-on:click="active = ! active">{{ optionname }}</span>

            <div class="value-list" v-show="active">
                <div class="option-values-list-container">
                    <p class="empty-list-state" v-show="values.length === 0">Add values for customers to select
                        from. These will be the options they may select from the drop down menu.</p>
                    <ul class="list-group option-values-list">
                        <li class="list-group-item" v-for="value in values">
                            <span>{{ value.name }}</span>
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

<script>
    module.exports = {
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
    }
</script>