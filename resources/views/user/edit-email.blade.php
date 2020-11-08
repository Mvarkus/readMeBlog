@extends('layouts.base')

@section('title', 'Create an account')

@section('content')
    <section>
        @include('layouts.errors')
        
        <form class="user-form" action="/user-email" method="POST">
            @csrf
            @method('put')
            <label>
                <span>* Email</span>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </label>
            <div class="form-submit-button">
                <input type="submit" value="Update">
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