@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/users/show.css') }}">
@endsection

@section('content')
    <div class="profile-header">
        <div class="image-circle"></div>
        <h2 class="username">ユーザー名</h2>
        <a class="edit-profile" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="tab">
        <div class="tab__recommend">出品した商品</div>
        <div class="tab__mylist">購入した商品</div>
    </div>
    <div class="content__wrapper2">
        {{-- これをループでたくさん表示させる --}}
        <div class="product-container">
            <div class="product-image"></div>
            <div class="product-name">商品名</div>
        </div>
    </div>
@endsection('content')
