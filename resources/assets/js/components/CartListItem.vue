<style>

</style>

<template>
    <div class="cart-list-item">
        <div class="item-container item-image-container">
            <img v-bind:src="thumbnail" alt="image thumbnail">
        </div>
        <div class="item-container item-name-container">
            <h3 class="item-name">{{ itemname }}</h3>
                <span v-if="hasCustomisations" v-on:click="showModal = true">
                    <img src="/images/assets/info_icon.png"
                         alt="tap to review your info"
                         width="20px"
                         height="20px"
                    ></span>
            <modal :show.sync="showModal">
                <h3 slot="header">Your Choices and Customisations</h3>
                <div slot="body">
                    <h4>Choices</h4>
                    <p v-for="option in options.options">{{ this.showOptions(option) }}</p>
                    <h4>Customisations</h4>
                    <p v-for="customisation in options.customisations">{{ this.showCustomisations(customisation) }}</p>
                </div>
            </modal>
        </div>
        <div class="item-container item-details-container">

        </div>
        <div class="item-container item-qty-container">
            <input type="number" v-model="quantity" v-if="canEdit" class="item-quantity">
            <p v-else class="item-quantity">{{ quantity }}</p>
            <button v-on:click="handleEditButtonClick">
                {{ buttonTxt }}
            </button>
        </div>
        <div class="item-container item-price-container">
            <p class="item-price">&pound;{{ (price * quantity) / 100}}</p>
        </div>
    </div>
</template>

<script>
    module.exports = {

        props: ['itemname', 'quantity', 'price', 'rowid', 'thumbnail', 'options'],

        data: function() {
            return {
                editing: false,
                waiting: false,
                showModal: false
            }
        },

        computed: {
            buttonTxt: function() {
                if(this.waiting) {
                    return 'wait';
                }
                return this.editing ? 'Save' : 'Edit';
            },

            canEdit: function() {
                return this.editing && (! this.waiting)
            },

            hasCustomisations: function() {
                return (this.options.options.length > 0) || (this.options.customisations.length > 0);
            }
        },

        methods: {
            handleEditButtonClick: function() {
                if(this.editing) {
                    this.updateQuantity();
                } else {
                    this.editing = true;
                }
            },

            updateQuantity: function() {
                this.waiting = true;
                this.$http.post('/api/cart/' + this.rowid, {qty: this.quantity}, function(res) {
                    this.editing = false;
                    this.waiting = false;
                    this.$dispatch('quantity.updated');
                });
            },

            showOptions: function(option) {
                var res = [];
                for(var key in option)  {
                    if(option.hasOwnProperty(key)) {
                        res.push(key);
                    }
                }

                return res[0] + ': ' + option[res[0]];
            },

            showCustomisations: function(customisation) {
                var res = [];
                for(var key in customisation)  {
                    if(customisation.hasOwnProperty(key)) {
                        res.push(key);
                    }
                }

                return res[0] + ': ' + customisation[res[0]];
            }
        }
    }
</script>