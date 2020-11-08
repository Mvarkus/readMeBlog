@extends('layouts.base')

@section('title', 'Find things first, read the blog and improve yourself.')

@section('content')
    <section class="homepage">
        <h2>Latest posts</h2>

        @foreach ($posts['recent'] as $post)
        <article>
        <a class="blog-title" href="/posts/{{ $post->id }}"><h3>{{ $post->title }}</h3></a>
           
            @if ($post->image !== null)
                <div class="post-image">
                    <img src="{{ asset('images/blog/' . $post->image) }}" alt="">
                </div>
            @endif
        
            <div class="post-meta">
                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/like_icon.svg') }}" alt="Heart icon" title="Like">
                    </div>
                    <span>{{ $post->votes }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/user_icon.svg') }}" alt="User icon" title="Posted by">
                    </div>
                    <span>{{ $post->user->full_name }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/calendar_icon.svg') }}" alt="Heart icon" title="Posted on">
                    </div>
                    
                    <span>{{ $post->date }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/category_icon.svg') }}" alt="Grid icon" title="Category">
                    </div>
                    
                    <span>{{ $post->category->title }}</span>
                </div>
            </div>
            <p>{{ $post->excerpt }}</p>
            <div class="read-more-button">
                <a href="{{ $post->specificResourcePath() }}">Read more</a>
            </div>
        </article>
        @endforeach

    </section>

    <section class="homepage">
        <h2>Most popular posts</h2>
        @foreach ($posts['popular'] as $post)
        <article>
        <a class="blog-title" href="/posts/{{ $post->id }}"><h3>{{ $post->title }}</h3></a>
            
            @if ($post->image !== null)
                <div class="post-image">
                    <img src="{{ asset('images/blog/' . $post->image) }}" alt="">
                </div>
            @endif
            
            <div class="post-meta">
                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/like_icon.svg') }}" alt="Heart icon" title="Like">
                    </div>
                    <span>{{ $post->votes }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/user_icon.svg') }}" alt="User icon" title="Posted by">
                    </div>
                    <span>{{ $post->user->full_name }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/calendar_icon.svg') }}" alt="Heart icon" title="Posted on">
                    </div>
                    
                    <span>{{ $post->date }}</span>
                </div>
                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/category_icon.svg') }}" alt="Grid icon" title="Category">
                    </div>
                    
                    <span>{{ $post->category->title }}</span>
                </div>
            </div>
            <p>{{ $post->excerpt }}</p>
            <div class="read-more-button">
                <a href="{{ $post->specificResourcePath() }}">Read more</a>
            </div>
        </article>
        @endforeach
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        function downloadJSAtOnload() {

            const element = document.createElement("script");
            element.src = "{{ asset('assets/js/main.js') }}";
            document.body.appendChild(element);

        }

        if (window.addEventListener)
            window.addEventListener("load", downloadJSAtOnload, false);
        else if (window.attachEvent)
            window.attachEvent("onload", downloadJSAtOnload);
        else window.onload = downloadJSAtOnload;
    </script>
@endsection