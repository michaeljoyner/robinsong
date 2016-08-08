<style></style>

<template>
    <div class="toggle-switch">
        <span class="switch-status-label" :class="{'chosen': currentStatus}">{{ trueLabel }}</span>
        <label class="toggle-switch-label" :for="'toggle-switch-' + identifier">
            <input type="checkbox" :id="'toggle-switch-' + identifier" v-on:change="toggleState"
                   v-model="currentStatus">
            <div class="switch-bulb"></div>
        </label>
        <span class="switch-status-label" :class="{'chosen': ! currentStatus}">{{ falseLabel }}</span>
    </div>
</template>

<script type="text/babel">
    module.exports = {
        props: ['identifier', 'true-label', 'false-label', 'initial-state', 'toggle-url', 'toggle-attribute'],

        data: function() {
            return {
                currentStatus: false
            }
        },

        ready: function() {
            this.currentStatus = this.initialState;
        },

        computed: {
            currentLabel: function() {
                return this.currentStatus ? this.trueLabel : this.falseLabel;
            }
        },

        methods: {
            toggleState: function () {
                let initialState = !this.currentStatus;
                this.$http.post(this.toggleUrl, this.makePayloadFor(this.currentStatus), function(res) {
                    this.currentStatus = res.new_state
                }).error(function(res) {

                });
            },

            makePayloadFor: function(attributeState) {
                var body = {};
                body[this.toggleAttribute] = attributeState;
                return body;
            }
        }
    }
</script>