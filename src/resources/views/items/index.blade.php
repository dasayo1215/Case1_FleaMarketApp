@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <div class="tab">
        <div class="tab__recommend">おすすめ</div>
        <div class="tab__mylist">マイリスト</div>
    </div>
    <div class="content__wrapper2">
        {{-- これをループでたくさん表示させる --}}
        <div class="product-container">
            <div class="product-image"></div>
            <div class="product-name">商品名</div>
        </div>
    </div>
@endsection('content')
