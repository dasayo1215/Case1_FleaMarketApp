@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
    <div class="content__wrapper3">

        <div class="wrapper-1">
            <div class="wrapper-1-1">
                <img class="image-square" src="{{ asset('storage/items/' . $item->image_filename) }}"
                    alt="{{ $item->name }}">
                <div class="item-detail">
                    <h2 class="content__heading">{{ $item->name }}</h2>
                    <div class="content__price price">
                        ￥ <span class="price-num">{{ number_format($item->price) }}</span>
                    </div>
                </div>
            </div>

            {{-- 支払い方法選択フォーム --}}
            <form method="GET" action="{{ url('/purchase/' . $item->id) }}">
                <h3 class="item__title purchase-way">支払い方法</h3>
                <select class="content-form__input content-form__select" name="payment_method"
                    onchange="this.form.submit()">
                    <option value="" disabled {{ session('selected_payment_method_id') ? '' : 'selected' }}>選択してください
                    </option>
                    @foreach ($paymentMethods as $paymentMethod)
                        <option value="{{ $paymentMethod->id }}"
                            {{ (session('selected_payment_methods')[$item->id] ?? '') == $paymentMethod->id ? 'selected' : '' }}>
                            {{ $paymentMethod->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <p class="content-form__error-message payment-method-error">
                @error('payment_method')
                    {{ $message }}
                @enderror
            </p>

            <div class="item__title-wrapper">
                <h3 class="item__title">配送先</h3>
                <a class="content-btn change-address" href="{{ url('/purchase/address/' . $item->id) }}">変更する</a>
            </div>
            <div class="postal-code">〒 {{ $purchase->postal_code }}</div>
            <div class="address">
                {{ $purchase->address }}<br>
                {{ $purchase->building }}
            </div>
            @if (!empty($address_error))
                <p class="content-form__error-message address-group-error">
                    {{ $address_error }}
                </p>
            @endif
            <p class="content-form__error-message address-group-error">
                @error('address_group')
                    {{ $message }}
                @enderror
            </p>
        </div>

        <div class="wrapper-2">
            {{-- 購入フォーム（POST） --}}
            <form class="purchase-form" action="{{ url('/purchase/' . $item->id) }}" method="post">
                @csrf
                <table class="purchase-table">
                    <tr>
                        <th class="purchase-table__th">商品代金</th>
                        <td class="purchase-table__td price">
                            ￥ <span class="price-num">{{ number_format($item->price) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="purchase-table__th">支払い方法</th>
                        <td class="purchase-table__td">
                            {{ optional($paymentMethods->firstWhere('id', session('selected_payment_methods')[$item->id] ?? null))->name ?? '未選択' }}
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="postal_code" value="{{ $purchase->postal_code }}">
                <input type="hidden" name="address" value="{{ $purchase->address }}">
                <input type="hidden" name="building" value="{{ $purchase->building }}">
                <input type="hidden" name="payment_method"
                    value="{{ session('selected_payment_methods')[$item->id] ?? '' }}">
                <input class="content-form__btn" type="submit" value="購入する">
            </form>
        </div>
    </div>
@endsection('content')
