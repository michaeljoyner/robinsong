window.Shuffle = require('shufflejs');
var rsApp = rsApp || {};
var ContactForm = require('./components/contactform.js');
rsApp.frontConstructorObjects = require('./components/frontvueobjects');
var Vue = require('vue');
Vue.use(require('vue-resource'));

if(document.querySelector('#x-token')) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#x-token').getAttribute('content');
}

Vue.component('cart-list-item', require('./components/CartListItem.vue'));
Vue.component('modal', require('./components/Modal.vue'));
Vue.component('purchasable', require('./components/Purchasable.vue'));

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

if(document.querySelector('#rs-contact-form')) {
    var contactForm = new ContactForm(document.querySelector('#rs-contact-form'));
    contactForm.init();
}

var navTrigger = document.querySelector('#slide-nav-trigger');
var navMenu = document.querySelector('.nav-bar-menu.front-main-nav');

function toggleNav() {
    if(navMenu.classList.contains('open')) {
        navTrigger.innerHTML = 'menu';
        navMenu.classList.remove('open');
        navTrigger.classList.remove('close');
        return;
    }
    navTrigger.innerHTML = '&times;';
    navTrigger.classList.add('close');
    navMenu.classList.add('open');
}

navTrigger.addEventListener('click', toggleNav, false);

$('#back-to-top-container').click(function() {
    $('body, html').animate({'scrollTop': 0}, "slow");
});