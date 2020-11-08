@extends('layouts.base')

@section('title', 'Edit comment')

@section('content')
    <section>
        @include('layouts.errors')
        
        <form class="comment-form" method="POST" action="{{ $comment->specificResourcePath() }}">
            <textarea id="comment-box" placeholder="Write your comment here" name="content">{{ $comment->content }}</textarea>
            @csrf
            @method('patch')
            <div class="form-submit-button">
                <button type="submit">Update</button>
            </div>
        </form>  
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