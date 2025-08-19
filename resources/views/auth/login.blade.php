@extends('layouts.app')

@section('title','Login')

@section('content')
<header class="auth-header">
  <h1>FashionablyLate</h1>
  <a href="{{ route('register') }}" class="link-btn">register</a>
</header>

<main class="auth-main">
  <h2>Login</h2>

  <form method="POST" action="{{ route('login') }}" class="auth-form">
    @csrf

    <label>メールアドレス
      <input type="email" name="email" value="{{ old('email') }}" placeholder="例：test@example.com">
    </label>
    @error('email') <p class="err">{{ $message }}</p> @enderror

    <label>パスワード
      <input type="password" name="password" placeholder="例：coachtech06">
    </label>
    @error('password') <p class="err">{{ $message }}</p> @enderror

    <button type="submit" class="primary">ログイン</button>
  </form>
</main>

{{-- register と同じ style を共有 --}}
@endsection
