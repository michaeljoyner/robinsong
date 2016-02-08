@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">You are the mistress of words</h1>
        <hr>
    </div>
    <section class="post-edit-section">
        {!! Form::model($post, ['url' => '/admin/blog/posts/'.$post->id, 'class' => 'rs-form blog-edit-form form-horizontal']) !!}
        <div class="form-group">
            <label for="title">Title: </label>
            {!! Form::text('title', null, ['class' => "form-control"]) !!}
        </div>
        <div class="form-group">
            <label for="description">Description: </label>
            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label for="content">Content: </label>
            {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'post-body']) !!}
        </div>
        <div class="form-group">
            <button type="submit" class="btn rs-btn">Save Changes</button>
        </div>
        {!! Form::close() !!}
    </section>
    <div class="hidden-image-upload">
        <input type="file" id="post-file-input">
    </div>
@endsection

@section('bodyscripts')
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#post-body',
            plugins: ['link', 'image', 'paste', 'fullscreen'],
            menubar: false,
            toolbar: 'undo redo | styleselect | bold italic | bullist numlist | link mybutton | fullscreen',
            paste_data_images: true,
            height: 700,
            body_class: 'article-body-content',
//            content_css: '/css/editor.css',
            content_style: "body {font-size: 16px; max-width: 800px; margin: 0 auto;} * {font-size: 16px;} img {max-width: 100%; height: auto;}",
            setup : function(ed) {
                ed.addButton('mybutton', {
                    text : '',
                    icon: true,
                    image : '/images/assets/insert_photo_black.png',
                    onclick : function() {
                        document.querySelector('#post-file-input').click();
                    }
                });
            },
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/admin/blog/posts/{{ $post->id }}/images/uploads');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onload = function() {
                    var json;

                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    success(json.location);
                };

                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', document.getElementById('x-token').getAttribute('content'));


                xhr.send(formData);
            }
        });
    </script>
    <script>
        var uploadHandler = {
            elems: {
                file: document.querySelector('#post-file-input')
            },

            init: function() {
                uploadHandler.elems.file.addEventListener('change', uploadHandler.processFile, false);
            },

            processFile: function(ev) {
                var fileReader = new FileReader();
                fileReader.onload = function(ev) {
                    tinymce.activeEditor.insertContent("<img src="+ ev.target.result +">");
                }
                fileReader.readAsDataURL(ev.target.files[0]);
            }
        }
        uploadHandler.init();
    </script>
@endsection