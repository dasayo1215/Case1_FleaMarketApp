@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/users/edit.css') }}">
@endsection

@section('content')
    <div class="content__wrapper">
        <h2 class="content__heading">プロフィール設定</h2>
        <form class="image-form" action="/mypage/profile/image" method="post" enctype="multipart/form-data">
            @csrf
            <div class="image-wrapper">
                @if (session()->has('profile_uploaded_image_path'))
                    <img class="image-circle"
                        src="{{ asset('storage/' . session('profile_uploaded_image_path')) }}?v={{ time() }}"
                        alt="アップロード画像">
                @else
                    <div class="image-circle"></div>
                @endif
                <label class="image-label" for="image">画像を選択する</label>
                <input class="image-input-hidden" type="file" id="image" name="image"
                    onchange="this.form.submit()">
            </div>
        </form>
        <p class="content-form__error-message image-error">
            @error('image')
                {{ $message }}
            @enderror
        </p>

        <form class="content-form__form" action="/mypage/profile" method="post">
            @method('PATCH')
            @csrf
            <label class="content-form__label" for="name">ユーザー名</label>
            <input class="content-form__input" type="text" name="name" id="name"
                value="{{ old('name', $user->name) }}">
            <p class="content-form__error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="postal_code">郵便番号</label>
            <input class="content-form__input" type="text" name="postal_code" id="postal_code"
                value="{{ old('postal_code', $user->postal_code) }}">
            <p class="content-form__error-message">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="address">住所</label>
            <input class="content-form__input" type="text" name="address" id="address"
                value="{{ old('address', $user->address) }}">
            <p class="content-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="building">建物名</label>
            <input class="content-form__input" type="text" name="building" id="building"
                value="{{ old('building', $user->building) }}">
            <p class="content-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
            <input class="content-form__btn" type="submit" value="更新する">
        </form>
    </div>
@endsection('content')
