var Vue = require('vue');
Vue.use(require('vue-resource'));

if(document.querySelector('#x-token')) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#x-token').getAttribute('content');
}

var app = app || {};
app.vueConstructorObjects = require('./components/vueconstructorobjects.js');
window.app = app;

Vue.component('gallery-show', require('./components/Galleryshow.vue'));
Vue.component('singleupload', require('./components/Singleupload.vue'));
Vue.component('dropzone', require('./components/Dropzone.vue'));
Vue.component('shipping-location', require('./components/ShippingLocation.vue'));
Vue.component('toggle-button', require('./components/Togglebutton.vue'));
Vue.component('tag-manager', require('./components/TagManager.vue'));
Vue.component('product-option', require('./components/ProductOption.vue'));
Vue.component('standard-option', require('./components/Standardoption.vue'));
Vue.component('toggle-switch', require('./components/Toggleswitch.vue'));
Vue.component('stock-unit', require('./components/Stockunit.vue'));
Vue.component('stockunits', require('./components/Stockunitsapp.vue'));


window.Vue = Vue;


