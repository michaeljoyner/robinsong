window.Shuffle = require('shufflejs');
var rsApp = rsApp || {};
var Vue = require('vue');
Vue.use(require('vue-resource'));

if(document.querySelector('#x-token')) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#x-token').getAttribute('content');
}

if(document.querySelector('#basket')) {
    rsApp.basket = new Vue({
        el: '#basket',

        data: {
            number_items: '',
            total_price: '',
            product_count: '',
            open: false,
        },

        ready: function() {
            this.fetchInfo();
        },

        methods: {
            fetchInfo: function(flash) {
                this.$http.get('/api/cart/summary?q=' + Math.random(), function(res) {
                    this.$set('number_items', res.item_count);
                    this.$set('product_count', res.product_count);
                    this.$set('total_price', res.total_price);
                    if(flash) {
                        this.flash();
                    }
                })
            },

            flash: function() {
                var vm = this;
                this.open = true;
                setTimeout(function() {
                    vm.open = false;
                }, 3000);
            }
        }
    });
}
window.Vue = Vue;
window.rsApp = rsApp;