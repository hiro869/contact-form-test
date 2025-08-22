@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <h2 class="card-title">Login</h2>
    
    <div class="card">
    <form method="POST" action="{{ route('login') }}" class="form">
        @csrf

        <label class="label">メールアドレス</label>
        <input name="email" value="{{ old('email') }}" class="input" placeholder="例：test@example.com">
        @error('email') <p class="error">{{ $message }}</p> @enderror

        <label class="label">パスワード</label>
        <input name="password" type="password" class="input" placeholder="例：coachtecht06">
        @error('password') <p class="error">{{ $message }}</p> @enderror

        <button type="submit" class="btn">ログイン</button>
    </form>
</div>
@endsection
