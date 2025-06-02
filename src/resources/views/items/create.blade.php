@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
    <div class="content__wrapper">
        <h2 class="content__heading">商品の出品</h2>
        <label class="content-form__label" for="image">商品画像</label>
        <div class="image-wrapper">
            <form class="image-form" action="/sell/image" method="post" enctype="multipart/form-data" id="imageForm">
                @csrf
                @if (session('sell_uploaded_image_path'))
                    <img class="uploaded-image"
                        src="{{ asset('storage/' . session('sell_uploaded_image_path')) }}?v={{ time() }}"
                        alt="アップロード画像">
                @endif
                <label class="image-label" for="image">画像を選択する</label>
                <input class="image-input-hidden" type="file" id="image" name="image"
                    onchange="this.form.submit()">

                {{-- 他のフォームの値をhiddenで送信 --}}
                <input type="hidden" name="name" id="hidden-name" value="{{ old('name') }}">
                <input type="hidden" name="brand" id="hidden-brand" value="{{ old('brand') }}">
                <input type="hidden" name="description" id="hidden-description" value="{{ old('description') }}">
                <input type="hidden" name="price" id="hidden-price" value="{{ old('price') }}">
                <input type="hidden" name="product_condition_id" id="hidden-product_condition_id"
                    value="{{ old('product_condition_id') }}">
                @foreach (old('category_id', []) as $id)
                    <input type="hidden" name="category_id[]" value="{{ $id }}">
                @endforeach
            </form>
        </div>
        <p class="content-form__error-message">
            @foreach (['image', 'sell_uploaded_image_path'] as $field)
                @error($field)
                    {{ $message }}
                @enderror
            @endforeach
        </p>

        <form class="content-form__form" action="/sell" method="post">
            @csrf
            <h3 class="item__title">商品の詳細</h3>
            <label class="content-form__label">カテゴリー</label>
            <div class="categories">
                @foreach ($categories as $category)
                    <label class="category-button">
                        <input class="category-input" type="checkbox" name="category_id[]" value="{{ $category->id }}"
                        {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                        <span class="category-text">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            <p class="content-form__error-message">
                @error('category_id')
                    {{ $message }}
                @enderror
            </p>

            <label class="content-form__label" for="product_condition_id">商品の状態</label>
            <select class="content-form__input content-form__select" name="product_condition_id" id="product_condition_id">
                <option value="" disabled selected>選択してください</option>
                @foreach ($conditions as $condition)
                    <option value="{{ $condition->id }}"
                        {{ old('product_condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->name }}
                    </option>
                @endforeach
            </select>
            <p class="content-form__error-message">
                @error('product_condition_id')
                    {{ $message }}
                @enderror
            </p>

            <h3 class="item__title">商品名と説明</h3>
            <label class="content-form__label" for="name">商品名</label>
            <input class="content-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
            <p class="content-form__error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="brand">ブランド名</label>
            <input class="content-form__input" type="text" name="brand" id="brand" value="{{ old('brand') }}">
            <p class="content-form__error-message">
                @error('brand')
                    {{ $message }}
                @enderror
            </p>

            <label class="content-form__label" for="description">商品の説明</label>
            <textarea class="content-form__textarea" name="description" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
            <p class="content-form__error-message">
                @error('description')
                    {{ $message }}
                @enderror
            </p>

            <label class="content-form__label" for="price">販売価格</label>
            <div class="input-wrapper">
                <span class="prefix">¥</span>
                <input class="price-input" type="text" name="price" inputmode="numeric" id="price"
                    value="{{ old('price') }}">
                <script>
                    const input = document.getElementById('price');
                    input.addEventListener('input', function() {
                        let value = input.value.replace(/,/g, '');
                        if (!isNaN(value) && value !== '') {
                            input.value = Number(value).toLocaleString();
                        }
                    });
                </script>
            </div>
            <p class="content-form__error-message">
                @error('price')
                    {{ $message }}
                @enderror
            </p>
            @if (session()->has('sell_uploaded_image_path'))
                <input type="hidden" name="sell_uploaded_image_path" value="{{ session('sell_uploaded_image_path') }}">
            @endif
            <input class="content-form__btn" type="submit" value="出品する">
        </form>
    </div>
@endsection('content')

@section('scripts')
    <script>
        const syncFields = [{
                id: 'name',
                type: 'text'
            },
            {
                id: 'brand',
                type: 'text'
            },
            {
                id: 'description',
                type: 'textarea'
            },
            {
                id: 'price',
                type: 'text'
            },
            {
                id: 'product_condition_id',
                type: 'select'
            },
        ];

        syncFields.forEach(field => {
            const input = document.getElementById(field.id);
            const hidden = document.getElementById('hidden-' + field.id);
            if (input && hidden) {
                input.addEventListener('input', () => {
                    hidden.value = input.value;
                });
            }
        });

        // カテゴリ（checkbox）選択の同期
        const categoryInputs = document.querySelectorAll('.category-input');
        categoryInputs.forEach(input => {
            input.addEventListener('change', () => {
                const form = document.getElementById('imageForm');
                // 一度すべての category_id[] hidden を削除
                document.querySelectorAll('input[name="category_id[]"]').forEach(e => {
                    if (e.closest('form') === form) {
                        e.remove();
                    }
                });
                // チェックされているものだけ追加
                categoryInputs.forEach(i => {
                    if (i.checked) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'category_id[]';
                        hidden.value = i.value;
                        form.appendChild(hidden);
                    }
                });
            });
        });
    </script>
@endsection
