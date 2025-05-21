@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
    <div class="content__wrapper">
        <h2 class="content__heading">商品の出品</h2>
        <form class="content-form__form" action="/mypage/profile" method="post" enctype="multipart/form-data">
            @csrf
            <label class="content-form__label" for="image">商品画像</label>
            <div class="image-wrapper">
                <label class="image-label" for="image">画像を選択する</label>
                <input class="image-input-hidden" type="file" id="image" name="image">
            </div>

            <h3 class="item__title">商品の詳細</h3>
            <label class="content-form__label" for="">カテゴリー</label>
            {{-- ここループで全カテゴリ表示、選んだものは色変える --}}
            <div class="categories">
            <p class="category">ファッション</p>
            <p class="category">家電</p>
            <p class="category">インテリア</p>
            <p class="category">レディース</p>
            <p class="category">メンズ</p>
            <p class="category">コスメ</p>
            <p class="category">本</p>
            <p class="category">ゲーム</p>
            <p class="category">スポーツ</p>
            <p class="category">スポーツ</p>
            <p class="category">スポーツ</p>
            <p class="category">スポーツ</p>
            <p class="category">スポーツ</p>
            <p class="category">スポーツ</p>
        </div>

            <label class="content-form__label" for="">商品の状態</label>
            <select class="content-form__input content-form__select" name="" id="">
                <option value="" disabled selected>選択してください</option>
                <option value="new">新品</option>
                <option value="old">古い</option>
            </select>

            <h3 class="item__title">商品名と説明</h3>
            <label class="content-form__label" for="name">商品名</label>
            <input class="content-form__input" type="text" name="name" id="name">
            <p class="content-form__error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="postal_code">ブランド名</label>
            <input class="content-form__input" type="text" name="postal_code" id="postal_code">
            <p class="content-form__error-message">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="address">商品の説明</label>
            <textarea class="content-form__textarea" name="comment" id="" cols="30" rows="10"></textarea>

            <p class="content-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="price">販売価格</label>
            <div class="input-wrapper">
                <span class="prefix">¥</span>
                <input class="price-input" type="text" name="price" inputmode="numeric">
            </div>
            <p class="content-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
            <input class="content-form__btn" type="submit" value="出品する">
        </form>
    </div>
@endsection('content')
