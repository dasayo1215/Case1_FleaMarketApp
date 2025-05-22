@extends('layouts.app')

@section('content')
    <div class="content__wrapper">
        <h2 class="content__heading">ログイン</h2>
        <form class="content-form__form" action="/login" method="post">
            @csrf
            <label class="content-form__label" for="email">メールアドレス</label>
            <input class="content-form__input" type="text" name="email" id="email"  value="{{ old('email') }}">
            <p class="content-form__error-message">
                @error('email')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="password">パスワード</label>
            <input class="content-form__input" type="password" name="password" id="password">
            <p class="content-form__error-message">
                @error('password')
                    {{ $message }}
                @enderror
            </p>
            <input class="content-form__btn" type="submit" value="ログインする">
        </form>
        <a class="content-btn" href="/register">会員登録はこちら</a>
    </div>
@endsection('content')
