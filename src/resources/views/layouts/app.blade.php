<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <h1 class="sr-only">COACHTECH</h1>
            <div class="header-logo">
                <img class="header-logo__img" src="{{ asset('storage/assets/logo.svg') }}" alt="ロゴ">
            </div>
            @if (!Route::is('login') && !Route::is('register'))
                <ul class="header-nav">
                    <li class="header-nav__item">
                        <form class="header-nav__search-form" action="/search"></form>
                        <input class="header-nav__search-input" type="text" placeholder="なにをお探しですか？">
                        <button class="header-nav__search-btn">検索</button>
                    </li>

                    @auth
                    <li class="header-nav__item">
                        <form class="header-nav__auth-form" action="/logout" method="post">
                            @csrf
                            <button class="header-nav__auth-btn">ログアウト</button>
                        </form>
                    </li>
                    @endauth
                    @guest
                    <li class="header-nav__item">
                        <form class="header-nav__auth-form" action="/logout" method="post">
                            @csrf
                            <a class="header-nav__auth-btn" href="{{ route('login') }}">ログイン</a>
                        </form>
                    </li>
                    @endguest

                    <li class="header-nav__item">
                        <a class="header-nav__mypage" href="{{ route('mypage') }}">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__sale" href="{{ route('sell') }}">出品</a>
                    </li>
                </ul>
            @endif
        </header>
        <div class="content">
            @yield('content')
        </div>

    </div>
</body>

</html>
