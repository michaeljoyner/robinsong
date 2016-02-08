@extends('front.base')

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <article class="full-blog-post">
        <header class="full-blog-post-header">
            <h1>{{ $post->title }}</h1>
            <p class="post-date">{{ $post->updated_at->toFormattedDateString() }}</p>
        </header>
        <section class="full-blog-post-body">
            {!! $post->content !!}
        </section>
        <footer class="full-blog-post-footer">
            <div class="social-sharing">
                <a href="#" class="social-share-link">
                    <img src="/images/assets/icon_facebook.png" alt="share on facebook">
                </a>
                <a href="#" class="social-share-link">
                    <img src="/images/assets/icon_pinterest.png" alt="share on pinterest">
                </a>
                <a href="#" class="social-share-link">
                    <img src="/images/assets/icon_twitter.png" alt="share on twitter">
                </a>
            </div>
            <a href="/blog">
                <div class="read-more-btn w-button button">Back to Blog Menu</div>
            </a>
        </footer>
    </article>


    @include('front.partials.footer')
@endsection