<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('/assets/css/main.css') }}">

    <title>@yield('title') - ReadMe</title>
</head>

<body>
    <header>

        <div class="header-top">
            <div class="logo">
                <span>ReadMe</span>
            </div>

            <div class="icons">
                <div>
                    <a target="_blank" rel="nofollow noopener" href="https://instagram.com">
                        <img src="{{ asset('/images/icons/instagram_icon.svg') }}" alt="instagram icon">
                    </a>
                </div>
                <div>
                    <a target="_blank" rel="nofollow noopener" href="https://youtube.com">
                        <img src="{{ asset('/images/icons/youtube_icon.svg') }}" alt="youtube icon">
                    </a>
                </div>
                <div>
                    <a target="_blank" rel="nofollow noopener" href="https://spotify.com">
                        <img src="{{ asset('/images/icons/spotify_icon.svg') }}" alt="spotify icon">
                    </a>
                </div>
                <div>
                    <a target="_blank" rel="nofollow noopener" href="https://twitter.com">
                        <img src="{{ asset('/images/icons/twitter_icon.svg') }}" alt="twitter icon">
                    </a>
                </div>
            </div>

        </div>

        <div class="header-middle">
            <nav>
                <div class="menu-burger drop-down-trigger">
                    <span class="line"></span>
                </div>
                <ul class="nav-menu drop-down">
                    <li><a href="/">Home</a></li>
                    <li><a href="/posts">Blog</a></li>
                    <li><a href="/about">About me</a></li>
                </ul>
            </nav>
        </div>

        <div class="header-bottom">
            <div class="header-phrase">
                <h1>@yield('title')</h1>
            </div>
            <div class="user-area">
                @guest
                    <div class="user-box">
                        <a href="/login">Login</a> / 
                        <a href="/register">Sign up</a>
                    </div>
                @endauth

                @auth
                    
                    <span class="user-box drop-down-trigger">{{ Auth::user()->fullName }} &#9660;</span>
                    <div class="drop-down">
                        <div><a href="/user">Profile</a></div>
                        <div>
                            <form action="/logout" method="POST">
                                @csrf
                                <button class="logout-button">Logout</button>
                            </form>
                        </div>
                    </div>
                    
                @endauth
            </div>
        </div>

    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <div class="footer-blocks">
            <div class="footer-block">
                <span>Navigation</span>
                <ul>
                    <li><a href="">Home</a></li>
                    <li><a href="">Blog</a></li>
                    <li><a href="">About me</a></li>
                </ul>
            </div>
    
            <div class="footer-block">
                <span>Social media</span>
                <ul>
                    <li><a href="">Youtube</a></li>
                    <li><a href="">Instagram</a></li>
                    <li><a href="">Spotify</a></li>
                    <li><a href="">Twitter</a></li>
                </ul>
            </div>
        </div>
        <span class="copyright">Copyright © 2020 Maksim Varkus</span>
    </footer>
    @yield('scripts')
</body>

</html>