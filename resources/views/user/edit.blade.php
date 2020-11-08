@extends('layouts.base')

@section('title', 'Create an account')

@section('content')
    <section>
        @include('layouts.errors')
        
        <form class="user-form" action="/user" method="POST">
            @csrf
            @method('put')
            <label>
                <span>* First name</span>
                <input type="text" name="firstName" value="{{ $user->first_name }}" required>
            </label>
            <label>
                <span>* Second name</span>
                <input type="text" name="secondName" value="{{ $user->second_name }}" required>
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