@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">Blog</h1>
        <div class="rs-header-actions pull-right">
            <button class="btn rs-btn btn-orange" data-toggle="modal" data-target="#blogpost-form-modal">
                New Post
            </button>
        </div>
        <hr>
    </div>
    <section class="blog-index-list">
        @foreach($posts as $post)
            <div class="blog-post-card">
                <header class="blog-post-card-header clearfix">
                    <h3>{{ $post->title }}</h3>
                    <p class="post-date">{{ $post->created_at->toFormattedDateString() }}</p>
                </header>
                <div class="blog-post-card-body">
                    {{ $post->description }}
                </div>
                <footer class="blog-post-card-footer clearfix">
                    <div class="post-actions pull-right">
                        <toggle-button url="/admin/blog/posts/{{ $post->id }}/publish"
                                       initial="{{ $post->published ? 1 : 0 }}"
                                       toggleprop="publish"
                                       onclass=""
                                       offclass="btn-danger"
                                       offtext="Publish"
                                       ontext="Unpublish"></toggle-button>
                        <a href="/admin/blog/posts/{{ $post->id }}/edit">
                            <div class="btn rs-btn btn-light">Edit</div>
                        </a>
                        @include('admin.partials.deletebutton', ['objectName' => $post->title, 'deleteFormAction' => '/admin/blog/posts'.$post->id])
                    </div>
                </footer>
            </div>
        @endforeach
    </section>
    @include('admin.forms.blogpostmodal')
    @include('admin.partials.deletemodal')
@endsection

@section('bodyscripts')
    @include('admin.partials.modalscript')
    <script>
        new Vue({el: 'body'});
    </script>
@endsection