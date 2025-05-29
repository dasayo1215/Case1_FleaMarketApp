@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
    <div class="content__wrapper4">
        {{-- @if (session('message'))
            <div class="verify-text">
                {{ session('message') }}
            </div>
        @endif --}}
        <div class="verify-text">
            登録していただいたメールアドレスに認証メールを送付しました。
        </div>
        <div class="verify-text">
            メール認証を完了してください。
        </div>

        <form class="content-form" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="content-btn" type="submit">認証メールを再送する</button>
        </form>
    </div>
@endsection('content')
