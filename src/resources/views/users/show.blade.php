@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/users/show.css') }}">
@endsection

@section('content')
    <div class="profile-header">
        @if ($user->image_filename)
            <img class="image-circle" src="{{ asset('storage/users/' . $user->image_filename) }}" alt="プロフィール画像">
        @else
            <div class="image-circle"></div>
        @endif
        <h2 class="username">{{ $user->name }}</h2>
        <a class="edit-profile" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="tab">
        @php
            $activeTab = request('tab') ?? 'sell';
        @endphp
        <a class="tab__sell {{ $activeTab === 'sell' ? 'active' : '' }}" href="{{ url('/mypage?tab=sell') }}">出品した商品</a>
        <a class="tab__buy {{ $activeTab === 'buy' ? 'active' : '' }}" href="{{ url('/mypage?tab=buy') }}">購入した商品</a>
    </div>
    <div class="content__wrapper3">
        @foreach ($items as $item)
            <a class="product-container-link" href="{{ url('/item/' . $item->id) }}">
                <div class="product-container">
                    <img class="product-image" src="{{ asset('storage/products/' . $item->image_filename) }}"
                        alt="{{ $item->name }}">
                    @if ($item->purchase && $item->purchase->completed_at !== null)
                        <div class="sold-label">Sold</div>
                    @endif
                    <div class="product-name">{{ $item->name }}</div>
                </div>
            </a>
        @endforeach

        {{-- 各商品を適度に間隔開けて左揃えにするためのダミー --}}
        @for ($i = 0; $i < 5; $i++)
            <div class="product-container-empty"></div>
        @endfor
    </div>
@endsection('content')
