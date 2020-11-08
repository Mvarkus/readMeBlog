@extends('layouts.base')

@section('title', 'User\'s page')

@section('content')
 

    <section class="user-profile">

        <div>
            
            <span class="user-section-title">Avatar</span>
            @include('layouts.errors')
            <div class="avatar">
                <img src="{{ asset($user->avatar) }}" alt="">
                <div class="avatar-control">
                    <form action="/user/avatar" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="avatar">
                        <button type="submit">Upload</button>
                    </form>
                    
                    <form action="/user/avatar/default" method="POST">
                        @csrf
                        @method('patch')
                        <button type="submit">Set default</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div>
            <span class="user-section-title">Profile</span>
            <div class="user-credentials">
                <div>NAME <a style="text-decoration: underline" href="/user/edit">Edit</a></div>
                <ul>
                    <li>First name: <span>{{ $user->first_name }}</span></li>
                    <li>Second name: <span>{{ $user->second_name }}</span></li>
                </ul>

                <div>Email <a style="text-decoration: underline" href="/user/edit-email">Edit</a></div>
                <ul>
                    <li>Email: <span>{{ $user->email }}</span></li>
                </ul>

                <div>ACCOUNT REMOVAL</div>
                <form action="/user" method="POST">
                    @csrf
                    @method('delete')
                    <div class="form-submit-button">
                        <button type="submit" class="delete-button">
                            Delete account
                        </button>
                    </div>
                </form>
            </div>
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