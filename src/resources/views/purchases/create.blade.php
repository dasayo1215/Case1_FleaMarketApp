@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
    <div class="content__wrapper2">
        <form class="purchase-form" action="{{ url('/purchase/' . $item->id) }}" method="post">
            @csrf
            <div class="content__wrapper-1">
                <div class="content__wrapper-1-1">
                    <img class="image-square" src="{{ asset('storage/products/' . $item->image_filename) }}"
                        alt="{{ $item->name }}"></img>
                    <div class="item-detail">
                        <h2 class="content__heading">{{ $item->name }}</h2>
                        <div class="content__price price">￥ <span class="price-num">{{ number_format($item->price) }}</span>
                        </div>

                    </div>
                </div>
                <h3 class="item__title purchase-way">支払い方法</h3>

                <select class="content-form__input content-form__select" name="payment_method" id="payment_method">
                    <option value="" disabled selected>選択してください</option>
                    @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->id }}"
                            {{ old('payment_method') == $payment_method->id ? 'selected' : '' }}>
                            {{ $payment_method->name }}
                        </option>
                    @endforeach
                </select>
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
                <input type="hidden" name="postal_code" value="{{ $purchase->postal_code }}">
<input type="hidden" name="address" value="{{ $purchase->address }}">
<input type="hidden" name="building" value="{{ $purchase->building }}">
                <p class="content-form__error-message address-group-error">
                    @error('address_group')
                        {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="content__wrapper-2">
                <table class="purchase-table">
                    <tr>
                        <th class="purchase-table__th">商品代金</th>
                        <td class="purchase-table__td price">￥ <span
                                class="price-num">{{ number_format($item->price) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="purchase-table__th">支払い方法</th>
                        {{-- ここが即時反映せねばならない！！！！ --}}
                        <td class="purchase-table__td">コンビニ払い</td>
                    </tr>
                </table>
                <input class="content-form__btn" type="submit" value="購入する">
            </div>
        </form>
    </div>
@endsection('content')
