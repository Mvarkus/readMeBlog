@extends('layouts.base')

@section('title', $post->title)

@section('content')
    <section class="single-post-container">
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
                <span>{{ $post->votes }}</span>@if ($voteStatus == true) (With your vote) @endif
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
        <div class="post-body">
            {!! $post->content !!}
        </div>
        @if (Session::has('voteResult'))
            <div class="post-action-result {{ Session::get('voteResult')['success'] == true ? 'success' : 'alert' }}">
                {!! Session::get('voteResult')['message'] !!}
            </div>
        @endif
        <div class="post-control-area"> 
            @if ($user !== null)
                <div>
                    <form method="POST" action="{{ $post->specificResourcePath() . '/vote' }}">
                        @csrf
                        @method('patch')
                        
                        <button>
                            <img src="{{ asset('images/icons/like_icon.svg') }}" alt="Heart icon" title="Like">
                        </button>
                        <span>@if ($voteStatus == true) -1 @else +1 @endif</span>
                    </form>
                </div>
                <div>
                    <a href="#comment-box"><img src="{{ asset('images/icons/comment_icon.svg') }}" alt="Dialog icon" title="Comment"></a>
                    <span>{{ $postComments->count() }}</span>
                </div>
            @else
                <div class="user-box">
                    <a href="/login">Login</a> / 
                    <a href="/register">Sign up</a>
                </div>
            @endif
        </div>
        <div class="post-comments">
            <span>Comments</span>
            
            <div class="comments">

                @include('layouts/errors')
                
                @if (Session::has('commentResult'))
                    <div class="post-action-result {{ Session::get('commentResult')['success'] == true ? 'success' : 'alert' }}">
                        {!! Session::get('commentResult')['message'] !!}
                    </div>
                @endif

                @foreach ($postComments as $comment)
                    <div>
                        <div class="comment-meta">
                            <div><img src="{{ asset($comment->user->avatar) }}" alt="User avatar"></div>
                            <div><b>{{ $comment->user->fullName }}</b></div>
                        </div>
                        <div class="comment-body">
                            {{ $comment->content }}
                        </div>
                        @if ($user !== null)
                        @if ($comment->user->id === $user->id)
                        <form action="{{ $comment->specificResourcePath() }}" method="POST">
                            @method('delete')
                            @csrf
                            <div class="form-submit-button">
                                <button type="submit">delete</button>
                            </div>
                        </form>
            
                        <a href="{{ $comment->specificResourcePath() . '/edit' }}"> edit </a>
                        
                    @endif
                        @endif
                    </div>
                @endforeach
            </div>
            <span  id="comment-box">Leave your comment</span>
            @if ($user !== null)
                <div>
                    <form  class="comment-form" method="POST" action="{{ $post->specificResourcePath() . '/comments' }}">
                        <textarea placeholder="Write your comment here" name="content"></textarea>
                        @csrf
                        <div class="form-submit-button">
                            <button type="submit">Submit</button>
                        </div>
                    </form>  
                </div>
            @else
                <div class="user-box">
                    <a href="/login">Login</a> / 
                    <a href="/register">Sign up</a>
                </div>
            @endif
        </div>
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