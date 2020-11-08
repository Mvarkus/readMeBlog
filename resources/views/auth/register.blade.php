@extends('layouts.base')

@section('title', 'Create an account')

@section('content')
    <section>
        @include('layouts.errors')
        
        <form class="user-form" action="/register" method="POST">
            @csrf

            <label>
                <span>* First name</span>
                <input type="text" name="firstName" value="{{ old('firstName') }}" required>
            </label>
            <label>
                <span>* Second name</span>
                <input type="text" name="secondName" value="{{ old('secondName') }}" required>
            </label>
            <label>
                <span>* Email</span>
                <input type="text" name="email" value="{{ old('email') }}" required>
            </label>
            <label>
                <span>* Password</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>* Confirm password</span>
                <input type="password" name="password_confirmation" required>
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