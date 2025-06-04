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
        <div class="image-wrapper">
            @php
                $imagePath =
                    old('profile_uploaded_image_path') ??
                    ($user->image_filename ? 'users/' . $user->image_filename : null);
            @endphp

            @if ($imagePath)
                <img id="profile-image" class="image-circle" src="{{ asset('storage/' . $imagePath) }}?v={{ time() }}"
                    alt="プロフィール画像">
            @else
                <div class="image-circle" id="profile-image"></div>
            @endif

            <label class="image-label" for="image">画像を選択する</label>
            <input class="image-input-hidden" type="file" id="image" name="image">
        </div>

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
            <input class="content-form__input" type="text" name="address" id="input-address" value="{{ $address }}">
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
            <input type="hidden" id="profile_uploaded_image_path" name="profile_uploaded_image_path"
                value="{{ old('profile_uploaded_image_path') }}">

            <input class="content-form__btn" type="submit" value="更新する">
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // 非同期で画像アップロードして即時反映
        document.getElementById('image').addEventListener('change', function() {
            const fileInput = this;
            const file = fileInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('image', file);

            fetch('/mypage/profile/image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // プレビュー画像を即時更新
                        document.getElementById('profile-image').src = data.image_url + '?v=' + Date.now();

                        // hidden input に path を保存
                        const hiddenPathInput = document.getElementById('profile_uploaded_image_path');
                        if (hiddenPathInput) {
                            hiddenPathInput.value = data.path;
                        }

                        const errorEl = document.querySelector('.image-error');
                        if (errorEl) {
                            errorEl.textContent = '';
                        }
                    } else if (data.errors?.image) {
                        const errorEl = document.querySelector('.image-error');
                        if (errorEl) {
                            errorEl.textContent = data.errors.image.join('\n');
                        }
                    } else {
                        const errorEl = document.querySelector('.image-error');
                        if (errorEl) {
                            errorEl.textContent = '画像のアップロードに失敗しました';
                        }
                    }
                });
        });
    </script>
@endsection
