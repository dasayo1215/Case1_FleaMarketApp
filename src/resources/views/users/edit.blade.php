@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/users/edit.css') }}">
@endsection

@section('content')
    @php
        $name = old('name', $user->name);
        $postalCode = old('postal_code', $user->postal_code);
        $address = old('address', $user->address);
        $building = old('building', $user->building);
    @endphp

    <div class="content__wrapper">
        <h2 class="content__heading">プロフィール設定</h2>
        <form class="image-form" action="/mypage/profile/image" method="post" enctype="multipart/form-data">
            @csrf
            <div class="image-wrapper">
                @php
                    $imagePath =
                        session('profile_uploaded_image_path') ??
                        ($user->image_filename ? 'users/' . $user->image_filename : null);
                @endphp
                @if ($imagePath)
                    <img class="image-circle" src="{{ asset('storage/' . $imagePath) }}?v={{ time() }}"
                        alt="プロフィール画像">
                @else
                    <div class="image-circle"></div>
                @endif
                <label class="image-label" for="image">画像を選択する</label>
                <input class="image-input-hidden" type="file" id="image" name="image"
                    onchange="this.form.submit()">
            </div>
            {{-- 他のフォームの値をhiddenで送信 --}}
            <input type="hidden" name="name" id="hidden-name" value="{{ $name }}">
            <input type="hidden" name="postal_code" id="hidden-postal_code" value="{{ $postalCode }}">
            <input type="hidden" name="address" id="hidden-address" value="{{ $address }}">
            <input type="hidden" name="building" id="hidden-building" value="{{ $building }}">
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
            <input class="content-form__input" type="text" name="name" id="input-name" value="{{ $name }}">
            <p class="content-form__error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="postal_code">郵便番号</label>
            <input class="content-form__input" type="text" name="postal_code" id="input-postal_code"
                value="{{ $postalCode }}">
            <p class="content-form__error-message">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="address">住所</label>
            <input class="content-form__input" type="text" name="address" id="input-address"
                value="{{ $address }}">
            <p class="content-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="building">建物名</label>
            <input class="content-form__input" type="text" name="building" id="input-building"
                value="{{ $building }}">
            <p class="content-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
            @if (session()->has('profile_uploaded_image_path'))
                <input type="hidden" name="profile_uploaded_image_path"
                    value="{{ session('profile_uploaded_image_path') }}">
            @endif
            <input class="content-form__btn" type="submit" value="更新する">
        </form>
    </div>
@endsection('content')

@section('scripts')
    <script>
        const inputs = ['name', 'postal_code', 'address', 'building'];

        inputs.forEach(field => {
            const inputElement = document.getElementById(`input-${field}`);
            const hiddenElement = document.getElementById(`hidden-${field}`);

            if (inputElement && hiddenElement) {
                inputElement.addEventListener('input', () => {
                    hiddenElement.value = inputElement.value;
                });
            }
        });
    </script>
@endsection
