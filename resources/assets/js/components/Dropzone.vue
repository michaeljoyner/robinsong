<style>

</style>

<template>
    <div class="drop-area"
         v-on:drop.prevent="handleFiles"
         v-on:dragenter.prevent="hover=true"
         v-on:dragover.prevent="hover=true"
         v-on:dragleave="hover=false"
         v-bind:class="{'hovering': hover}">
        <label for="dropzone-input">
            <p class="drag-prompt" v-show="uploads.length === 0">Drag files or click to upload!</p>
            <input v-on:change="handleFiles" type="file" id="dropzone-input" multiple style="display:none;"/>
            <ul>
                <li v-for="upload in uploads" v-show="upload.status !== 'success'">
                    <p
                            class="image-upload-info"
                            v-bind:class="{'failed': upload.status === 'failed'}"
                            >
                        <span class="upload-progress-bar"
                              v-bind:style="{width: upload.progress + '%'}"></span>
                        {{ upload.filename }}
                    </p>
                </li>
            </ul>
        </label>
    </div>
</template>

<script>
    module.exports = {

        props: ['url'],

        data: function () {
            return {
                uploads: [],
                hover: false
            }
        },

        methods: {

            handleFiles: function (ev) {
                ev.preventDefault();
                ev.stopPropagation();
                var files = ev.target.files || ev.dataTransfer.files;
                for (var i = 0; i < files.length; i++) {
                    this.processFile(files[i]);
                }
            },

            processFile: function (file) {
                var fd = new FormData();
                var upload = {
                    filename: file.name,
                    progress: 0,
                    status: 'uploading',
                    setProgress: function (progress) {
                        this.progress = progress;
                    },
                    setStatus: function (status) {
                        this.status = status;
                    }
                }
                fd.append('file', file);
                this.$http.post(this.url, fd, function (res) {
                    upload.setStatus('success');
                    this.uploads.$remove(upload);
                    this.alertParent(res);
                }, {
                    beforeSend: function (req, options) {
                        req.upload.addEventListener('progress', function (ev) {
                            upload.setProgress(parseInt(ev.loaded / ev.total * 100));
                        });
                    }
                }).error(function () {
                    upload.setStatus('failed');
                });
                this.uploads.push(upload);
            },

            alertParent: function (image) {
                this.$dispatch('image-added', image);
            }
        }
    }
</script>