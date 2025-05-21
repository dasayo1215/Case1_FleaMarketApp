@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
    <div class="content__wrapper2">
        <form class="purchase-form" action="/purchase" method="post">
            @csrf
            <div class="content__wrapper-1">
                <div class="content__wrapper-1-1">
                    <div class="image-square"></div>
                    <div class="item-detail">
                        <h2 class="content__heading">商品名</h2>
                        <div class="content__price price">￥ <span class="price-num">47,000</span></div>

                    </div>
                </div>
                <h3 class="item__title purchase-way">支払い方法</h3>

                <select class="content-form__input content-form__select" name="" id="">
                    <option value="" disabled selected>選択してください</option>
                    <option value="new">コンビニ払い</option>
                    <option value="old">カード支払い</option>
                </select>

                <div class="item__title-wrapper">
                    <h3 class="item__title">配送先</h3>
                    <a class="content-btn change-address" href="/purchase/address/">変更する</a>
                </div>
                <div class="postal-code">〒XXX-YYYY</div>
                <div class="address">ここには住所と建物が入ります</div>
            </div>

            <div class="content__wrapper-2">
                <table class="purchase-table">
                    <tr>
                        <th class="purchase-table__th">商品代金</th>
                        <td class="purchase-table__td price">￥ <span class="price-num">47,000</span></td>
                    </tr>
                    <tr>
                        <th class="purchase-table__th">支払い方法</th>
                        <td class="purchase-table__td">コンビニ払い</td>
                    </tr>
                </table>
                <input class="content-form__btn" type="submit" value="購入する">
            </div>
        </form>
    </div>
@endsection('content')
