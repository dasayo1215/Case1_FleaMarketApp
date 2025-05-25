@extends('layouts.app')

@section('content')
    <div class="content__wrapper">
        <h2 class="content__heading">住所の変更</h2>
        <form class="content-form__form" action="{{ url('/purchase/address/' . $item_id) }}" method="post">
            @csrf
            <label class="content-form__label" for="postal_code">郵便番号</label>
            <input class="content-form__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
            <p class="content-form__error-message">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="address">住所</label>
            <input class="content-form__input" type="text" name="address" id="address" value="{{ old('address') }}">
            <p class="content-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
            <label class="content-form__label" for="building">建物名</label>
            <input class="content-form__input" type="text" name="building" id="building" value="{{ old('building') }}">
            <p class="content-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
            {{-- AddressRequestをプロフィール編集画面と共通で使うため --}}
            <input type="hidden" name="name" value="ダミー名">
            <input class="content-form__btn" type="submit" value="更新する">
        </form>
    </div>
@endsection('content')
