@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="item-content__wrapper">
        <div class="image-square"></div>
        <div class="content__detail">
            <div class="content__heading">
                <h2>商品名がここに入る</h2>
                <div class="content__brand">ブランド名</div>
                <div class=" content__price">￥ <span class="content__price-num">47,000</span>（税込）</div>
                <div class="content__like-comment">
                    <div class="content__like">
                        <img class="content__like-img" src="{{ asset('storage/assets/star-on.png') }}" alt="ロゴ">
                        <div class="content__like-num">3</div>
                    </div>
                    <div class="content__comment">
                        <img class="content__comment-img" src="{{ asset('storage/assets/bubble.png') }}" alt="ロゴ">
                        <div class="content__comment-num">1</div>
                    </div>
                </div>
            </div>
            <a class="content__purchase-btn" href="/purchase">購入手続きへ</a>

            <h3 class="info__title">商品説明</h3>
            <div>カラー：グレー</div>

            <h3 class="info__title">商品の情報</h3>
            <table class="info__table">
                <tr>
                    <th class="info__table-th">カテゴリー</th>
                    <td class="td-category">洋服</td>
                </tr>
                <tr class="info__table-tr">
                    <th class="info__table-th">商品の状態</th>
                    <td class="td-state">良好</td>
                </tr>
            </table>

            <h3 class="comment__title">コメント(1)</h3>
            <div class="comment__user">
                <div class="comment__user-image"></div>
                <div class="comment__user-name">admin</div>
            </div>
            <form action="/item" method="POST">
                <div class="comment__content">ここにコメントが入る</div>
                <label class="content-form__label" for="comment">商品へのコメント</label>
                <textarea class="content-form__textarea" name="comment" id="comment" cols="30" rows="10"></textarea>
                <input class="content-form__btn" type="submit" value="コメントを送信する">
            </form>
        </div>
    </div>
@endsection('content')
