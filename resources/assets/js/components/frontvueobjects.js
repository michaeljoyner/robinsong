module.exports = {

    cartPageVue: {
        el: '#cart-list',

        data: {
            items: [],
            shipping: [],
        },

        computed: {
            total: function() {
                return this.items.reduce(function(sum, item) {
                    return sum + (item.price * item.qty);
                }, 0);
            }
        },

        ready: function() {
            this.fetchItems();
            this.fetchShippingPrices();
        },

        events: {
            'quantity.updated': function() {
                this.fetchShippingPrices();
            }
        },

        methods: {
            fetchItems: function() {
                this.$http.get('/api/cart', function(res) {
                    this.$set('items', res);
                });
            },

            fetchShippingPrices: function() {
                this.$http.get('/api/cart/shipping', function(res) {
                    this.$set('shipping', res);
                });
            },

            removeItem: function(item) {
                this.$http.delete('/api/cart/' + item.rowid, {}, function(res) {
                    this.items.$remove(item);
                    this.fetchShippingPrices();
                });
            }
        }
    }
}