@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <div class="tab">
        <a class="tab__recommend {{ request('tab') !== 'mylist' ? 'active' : '' }}" href="{{ url('/') }}">おすすめ</a>
        <a class="tab__mylist {{ request('tab') === 'mylist' ? 'active' : '' }}" href="{{ url('/?tab=mylist') }}">マイリスト</a>
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
