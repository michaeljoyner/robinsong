module.exports = {

    productImageVue: {
        el: '#product-image-vue'
    },

    productOptionsVue: {
        el: '#product-options-vue',

        data: {
            options: [],
            newoption: '',
            product: ''
        },

        ready: function () {
            var id = document.querySelector('#product-options-vue').getAttribute('data-product');
            this.$set('product', id);
            this.fetchOptions();
        },

        methods: {
            addOption: function () {
                this.$http.post('/admin/products/' + this.product + '/options', {'name': this.newoption}, function (res) {
                    this.options.push(res);
                }).error(function (res) {
                    console.log(res);
                });
                this.newoption = '';
            },

            fetchOptions: function () {
                this.$http.get('/admin/products/' + this.product + '/options', function (res) {
                    this.$set('options', res);
                })
            },

            removeOption: function (option) {
                this.$http.delete('/admin/productoptions/' + option.id, function (res) {
                    this.options.$remove(option);
                });
            }
        }
    },

    productCustomisationVue: {
        el: '#product-customisations-vue',

        data: {
            customisations: [],
            newcustomisation: {
                name: '',
                longform: false
            },
            product: ''

        },

        ready: function () {
            var id = document.querySelector('#product-customisations-vue').getAttribute('data-product');
            this.$set('product', id);
            this.fetchCustomisations();
        },

        methods: {
            fetchCustomisations: function () {
                this.$http.get('/admin/products/' + this.product + '/customisations', function (res) {
                    this.$set('customisations', res);
                });
            },

            addCustomisation: function () {
                this.$http.post('/admin/products/' + this.product + '/customisations', {
                    'name': this.newcustomisation.name,
                    'longform': this.newcustomisation.longform
                }, function (res) {
                    this.customisations.push(res);
                });
                this.newcustomisation.name = '';
                this.newcustomisation.longform = false;
            },

            removeCustomisation: function (customisation) {
                this.$http.delete('/admin/customisations/' + customisation.id, function (res) {
                    this.customisations.$remove(customisation);
                });
            }
        }
    },

    tagApp: {
        el: '#tag-app'
    },

    galleryApp: {
        el: '#gallery-app',

        events: {
            'image-added': function (image) {
                this.$broadcast('add-image', image);
            }
        }
    },

    toggleBtnVue: {
        el: '#toggle-available-app',
    }


}