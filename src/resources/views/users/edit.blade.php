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
            <input type="hidden" name="name" id="hidden-name" value="{{ old('name', $oldInputs['name'] ?? $user->name) }}">
            <input type="hidden" name="postal_code" id="hidden-postal_code" value="{{ old('postal_code', $oldInputs['postal_code'] ?? $user->postal_code) }}">
            <input type="hidden" name="address" id="hidden-address" value="{{ old('address', $oldInputs['address'] ?? $user->address) }}">
            <input type="hidden" name="building" id="hidden-building" value="{{ old('building', $oldInputs['building'] ?? $user->building) }}">
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
            <input class="content-form__input" type="text" name="name" id="input-name"
                value="{{ old('name', $oldInputs['name'] ?? $user->name) }}">
                <p class="content-form__error-message">
            @error('name')
                {{ $message }}
            @enderror
            </p>
            <label class="content-form__label" for="postal_code">郵便番号</label>
            <input class="content-form__input" type="text" name="postal_code" id="input-postal_code"
                value="{{ old('postal_code', $oldInputs['postal_code'] ?? $user->postal_code) }}">
            <p class="content-form__error-message">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="address">住所</label>
            <input class="content-form__input" type="text" name="address" id="input-address"
                value="{{ old('address', $oldInputs['address'] ?? $user->address) }}">
            <p class="content-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="building">建物名</label>
            <input class="content-form__input" type="text" name="building" id="input-building"
                value="{{ old('building', $oldInputs['building'] ?? $user->building) }}">
            <p class="content-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
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
