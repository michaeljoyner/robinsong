@extends('front.base')

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="w-section blog-title-section">
        <h1 class="h2">Blog</h1>
        <p class="p1 large-lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    </div>
    <section class="w-section section w-container">
    @foreach($posts as $post)
        <article class="blog-post-index-card">
            <header class="blog-post-index-card-header">
                <h3><a href="/blog/{{ $post->slug }}">{{ $post->title }}</a></h3>
                <p class="post-date">{{ $post->updated_at->toFormattedDateString() }}</p>
            </header>
            <section class="blog-post-index-card-body">
                <p>{{ $post->description }}</p>
            </section>
            <footer class="blog-post-index-card-footer">
                <a href="/blog/{{ $post->slug }}">
                    <div class="read-more-btn w-button button">Read on</div>
                </a>
            </footer>
        </article>
    @endforeach
    </section>
    @include('front.partials.footer')
@endsection