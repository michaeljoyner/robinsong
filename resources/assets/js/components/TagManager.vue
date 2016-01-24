<style>

</style>

<template>
    <div class="tag-manager-container">
        <h4 class="tag-manager-heading">Product Tags:</h4>
        <div class="product-tags">
                <span class="product-tag" v-for="tag in producttags">
                    {{ tag }}
                    <span class="delete-icon" v-on:click="forgetTag(tag)">&times;</span>
                </span>
        </div>
        <div class="tag-choices">
            <p class="tag-choice-instruction">Click on a tag below select for product</p>
                <span class="tag-choice" v-for="choice in tagchoices"
                      v-on:click="addTagToProduct(choice)">{{ choice }}</span>
        </div>
        <div class="add-tag-box">
            <label class="input-label">Add a tag: </label>
            <input type="text" class="new-tag-input" v-model="newtag" v-on:keypress.enter="addTag">
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: ['productid', 'taglist'],

        template: '#tag-manager-template',

        data: function () {
            return {
                alltags: [],
                producttags: [],
                newtag: ''
            }
        },

        computed: {
            tagchoices: function () {
                var self = this;
                return this.alltags.filter(function (tag) {
                    return self.producttags.indexOf(tag) === -1;
                });
            }
        },

        ready: function () {
            var tagArray = this.taglist.split(',');
            if (tagArray.length === 1 && tagArray[0] === '') {
                tagArray = [];
            }
            this.$set('producttags', tagArray);
            this.fetchAllTags();
        },

        methods: {
            fetchAllTags: function () {
                this.$http.get('/admin/tags', function (res) {
                    this.$set('alltags', res.tags);
                })
            },

            syncTags: function () {
                this.$http.post('/admin/products/' + this.productid + '/tags', {tags: this.producttags}, function (res) {
                    this.$set('producttags', res.tags);
                });
            },

            addTagToProduct: function (tag) {
                this.producttags.push(tag);
                this.syncTags();
            },

            addTag: function () {
                if (this.newtag === '') {
                    return;
                }

                this.alltags.push(this.newtag);
                this.addTagToProduct(this.newtag);
                this.newtag = '';
            },

            forgetTag: function (tag) {
                this.producttags.$remove(tag);
                this.syncTags();
            }
        }
    }
</script>