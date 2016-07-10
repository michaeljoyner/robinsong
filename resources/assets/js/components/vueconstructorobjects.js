module.exports = {

    productImageVue: {
        el: '#product-image-vue'
    },

    productOptionsVue: {
        el: '#product-options-vue',

        data: {
            options: [],
            newoption: '',
            product: '',
            standardOptions: []
        },

        ready: function () {
            var id = document.querySelector('#product-options-vue').getAttribute('data-product');
            this.$set('product', id);
            this.fetchOptions();
            this.fetchStandardOptions();
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

            fetchStandardOptions:function() {
                this.$http.get('/admin/standard-options', function(res) {
                    this.$set('standardOptions', res);
                }).error(function() {
                    console.log('unable to retrieve standard options from server');
                })
            },

            addStandardOptionToProduct: function(standardOption) {
                this.$http.post('/admin/products/' + this.product + '/standard-options/add', {
                    standard_option_id: standardOption.id
                }, function(res) {
                    this.options.push(res);
                }).error(function(res) {
                   console.log('unable to set standard option for product');
                });
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
    },

    standardOptionsVue: {
        el: 'body',

        data: {
            standardOptions: [],
            newName: ''
        },

        ready: function () {
            this.fetchOptions();
        },

        events: {
            'remove-option': function (optionId) {
                this.removeOptionById(optionId);
            }
        },

        methods: {
            makeOption: function () {
                if (this.newName === '') return;

                this.$http.post('/admin/standard-options', {name: this.newName}, function (res) {
                    this.standardOptions.push(res);
                    this.newName = '';
                }).error(function (res) {
                    console.log('unable to make option on server');
                })
            },

            removeOptionById: function (optionId) {
                this.$http.delete('/admin/standard-options/' + optionId, function(res) {
                    var opt = this.standardOptions.filter(function (option) {
                        return option.id === optionId;
                    });
                    if(opt.length > 0) {
                        this.standardOptions.$remove(opt[0]);
                    }
                }).error(function(res) {
                    console.log('unable to delete option on server');
                });

            },

            fetchOptions: function () {
                this.$http.get('/admin/standard-options', function (res) {
                    this.$set('standardOptions', res);
                }).error(function (res) {
                    console.log('unable to retrieve options');
                });
            }
        }
    }


}