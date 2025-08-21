@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="page-wrap"><!-- ← 背景ベージュ帯（高さを100vhまで） -->

  <h2 class="page-title">Register</h2>

  <div class="register-card">
    <div class="form-inner"><!-- ← フィールド群を少し小さく中央寄せ -->

      <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="form-row">
          <label class="label">お名前</label>
          <input name="name" class="input" value="{{ old('name') }}" placeholder="例：山田　太郎">
          @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
          <label class="label">メールアドレス</label>
          <input name="email" class="input" value="{{ old('email') }}" placeholder="例：test@example.com">
          @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
          <label class="label">パスワード</label>
          <input name="password" type="password" class="input" placeholder="例：coachtech06">
          @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="actions">
          <button type="submit" class="btn-primary">登録</button>
        </div>
      </form>

    </div>
  </div>

  <div class="footer-spacer"></div>
</div>
@endsection
