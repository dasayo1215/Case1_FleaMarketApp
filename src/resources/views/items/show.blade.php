@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="item-content__wrapper">
        <div class="image-container">
            <img class="image-square" src="{{ asset('storage/products/' . $item->image_filename) }}"
                alt="{{ $item->name }}"></img>
            @if ($item->purchase && $item->purchase->completed_at !== null)
                <div class="sold-label">sold</div>
            @endif
        </div>
        <div class="content__detail">
            <div class="content__heading">
                <h2 class="product-name">{{ $item->name }}</h2>
                <div class="content__brand">{{ $item->brand }}</div>
                <div class=" content__price">￥ <span class="content__price-num">{{ number_format($item->price) }}</span>（税込）
                </div>
                <div class="content__like-comment">
                    @php
                        $isLiked = $user ? $item->isLikedBy($user) : false;
                        $likeCount = $item->likes->count();
                        $commentCount = $item->comments->count();
                    @endphp
                    <div class="content__like">
                        @auth
                            <form class="like-form" method="POST" action="{{ route('like', $item->id) }}">
                                @csrf
                                <button class="like-button" type="submit">
                                    <img class="content__like-img"
                                        src="{{ $isLiked ? asset('storage/assets/star-on.png') : asset('storage/assets/star-off.png') }}"
                                        alt="いいね">
                                </button>
                            </form>
                        @else
                            {{-- ゲストユーザー：非アクティブ表示 --}}
                            <div class="like-button disabled">
                                <img class="content__like-img-guest" src="{{ asset('storage/assets/star-off.png') }}"
                                    alt="いいね">
                            </div>
                        @endauth
                        <div class="content__like-num">{{ $likeCount }}</div>
                    </div>
                    <div class="content__comment">
                        <img class="content__comment-img" src="{{ asset('storage/assets/bubble.png') }}" alt="ロゴ">
                        <div class="content__comment-num">{{ $commentCount }}</div>
                    </div>
                </div>
            </div>
            @php
                $purchase = $item->purchase;
            @endphp

            @if ($item->seller_id == $user->id)
                <div class="purchase-unavailable">自身の出品です</div>
            @elseif ($purchase)
                @if (!is_null($purchase->paid_at))
                    <div class="purchase-sold">Sold</div>
                @elseif ($purchase->buyer_id !== $user->id)
                    <div class="purchase-unavailable">他ユーザーが購入手続き中です</div>
                @elseif(!is_null($purchase->completed_at) && $purchase->buyer_id === $user->id)
                    <div class="purchase-unavailable">お支払いを完了してください</div>
                @else
                    <a class="content__purchase-btn" href="{{ url('/purchase/' . $item->id) }}">購入手続きを再開</a>
                @endif
            @else
                <a class="content__purchase-btn" href="{{ url('/purchase/' . $item->id) }}">購入手続きへ</a>
            @endif

            <h3 class="product-description">商品説明</h3>
            <div>{{ $item->description }}</div>

            <h3 class="product-info">商品の情報</h3>
            <table class="info__table">
                <tr class="info__table-tr table-row1">
                    <th class="info__table-th th-category">カテゴリー</th>
                    <td class="td-category">
                        @foreach ($item->categories as $category)
                            <span class="td-category-span">{{ $category->name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr class="info__table-tr">
                    <th class="info__table-th">商品の状態</th>
                    <td class="td-state">{{ $item->productCondition->name }}</td>
                </tr>
            </table>

            <h3 class="comment__title">コメント({{ $item->comments->count() }})</h3>
            @foreach ($item->comments as $comment)
                <div class="comment__user">
                    <div class="user-image-container">
                        @if ($comment->user->image_filename)
                            <img class="comment__user-image"
                                src="{{ asset('storage/users/' . $comment->user->image_filename) }}"
                                alt="{{ $comment->user->image_filename }}" class="user-icon">
                        @else
                            <div class="comment__user-image"></div>
                        @endif
                    </div>
                    <div class="comment__user-name">{{ $comment->user->name }}</div>
                </div>
                <div class="comment__content">{{ $comment->comment }}</div>
            @endforeach

            <label class="content-form__label" for="comment">商品へのコメント</label>
            <form method="POST" action="{{ route('comment', $item->id) }}">
                @csrf
                <textarea class="content-form__textarea" name="comment" id="comment" cols="30" rows="10"></textarea>
                <p class="content-form__error-message">
                    @error('comment')
                        {{ $message }}
                    @enderror
                </p>
                @if ($purchase && !is_null($purchase->completed_at))
                    <div class="comment-unavailable">コメントできません</div>
                @else
                    <input class="content-form__btn" type="submit" value="コメントを送信する">
                @endif
            </form>

        </div>
    </div>
@endsection('content')
