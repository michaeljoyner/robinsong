<style>

</style>

<template>
    <div class="btn rs-btn btn-light" v-bind:class="{ 'btn-clear-danger': state, 'btn-light': !state}" v-on:click="toggleState">
        {{ buttonText }}
    </div>
</template>

<script>
    module.exports = {
        props: ['url', 'initial', 'ontext', 'offtext', 'onclass', 'offclass', 'toggleprop'],

        template: '#toggle-btn-template',

        data: function() {
            return {
                state: 0,
                syncing: false
            }
        },

        computed: {
            buttonText: function() {
                if(this.syncing) {
                    return 'Wait...';
                }

                return this.state === 1 ? this.ontext : this.offtext;
            }
        },

        ready: function() {
            this.$set('state', parseInt(this.initial));
        },

        methods: {
            toggleState: function() {
                var messageObj = {};
                if(this.syncing) {
                    return;
                }
                this.syncing = true;
                messageObj[this.toggleprop] = !(this.state);
                this.$http.post(this.url, messageObj, function(res) {
                    this.$set('state', res.new_state ? 1 : 0);
                    this.syncing = false;
                });
            }
        }
    }
</script>