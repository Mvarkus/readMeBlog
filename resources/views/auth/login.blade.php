@extends('layouts.base')

@section('title', 'Log in to the blog')

@section('content')
    <section>
        @include('layouts.errors')

        <form class="user-form" action="/login" method="POST">
            @csrf
            <label>
                <span>Email</span>
                <input type="text" name="email">
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password">
            </label>

            <label>
                <input type="checkbox" name="remember_me">
                <span style="display: inline">Remember me</span>
            </label>
            <div class="form-submit-button">
                <button type="submit">Login</button>
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