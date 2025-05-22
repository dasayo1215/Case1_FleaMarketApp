@extends('layouts.app')

@section('content')
    <div class="content__wrapper">
        <h2 class="content__heading">会員登録</h2>
        <form class="content-form__form" action="{{ route('register') }}" method="post">
            @csrf
            <label class="content-form__label" for="name">ユーザー名</label>
            <input class="content-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
            <p class="content-form__error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="email">メールアドレス</label>
            <input class="content-form__input" type="text" name="email" id="email" value="{{ old('email') }}">
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
            <label class="content-form__label" for="password_confirmation">確認用パスワード</label>
            <input class="content-form__input" type="password" name="password_confirmation" id="password_confirmation">
            <p class="content-form__error-message">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </p>

            <input class="content-form__btn" type="submit" value="登録する">
        </form>
        <a class="content-btn" href="/login">ログインはこちら</a>
    </div>
@endsection('content')
