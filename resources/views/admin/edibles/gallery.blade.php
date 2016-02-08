@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">Upload to the  {{ $gallery->name }} gallery of the {{ $page->name }} page</h1>
        <div class="rs-header-actions pull-right">
            <a href="/admin/site-content/pages/{{ $page->id }}">
                <div class="btn rs-btn btn-orange">
                    Back to {{ ucwords($page->name) }}
                </div>
            </a>
        </div>
        <hr>
    </div>
    @if($gallery->is_single)
        <div id="gallery-image-vue" class="single-image-uploader-box">
            <h4>{{ $gallery->description }}</h4>
            <singleupload default="{{ $gallery->defaultSrc('thumb') }}"
                          url="/admin/site-content/galleries/{{ $gallery->id }}/uploads"
                          shape="square"
                          size="large"
            ></singleupload>
        </div>
        <p class="lead">This gallery can hold only a single image, which will always be the most recent image you have uploaded. Also, the size you see in the preview above is just a preview, and not necessarily how it will look on your site.</p>
    @else
        <div id="gallery-app" class="product-gallery-container">
            <h4 class="gallery-heading">{{ $gallery->description }}</h4>

            <gallery-show
                    gallery="{{ $gallery->id }}"
                    geturl="/admin/site-content/galleries/{{ $gallery->id }}/uploads"
            ></gallery-show>
            <dropzone
                    url="/admin/site-content/galleries/{{ $gallery->id }}/uploads"
            ></dropzone>
        </div>
    @endif
@endsection

@section('bodyscripts')
    @if($gallery->is_single)
        <script>
            new Vue({
                el: '#gallery-image-vue'
            });
        </script>
    @else
        <script>
            new Vue({
                el: '#gallery-app',

                events: {
                    'image-added': function (image) {
                        this.$broadcast('add-image', image);
                    }
                }
            });
        </script>
    @endif
@endsection