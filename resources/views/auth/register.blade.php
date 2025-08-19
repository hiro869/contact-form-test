@extends('layouts.app')

@section('content')
  <h2 class="page-title">Register</h2>

  <div class="form-card">
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <label class="form-label">お名前</label>
      <input type="text" name="name" class="form-control" placeholder="例：山田 太郎" value="{{ old('name') }}">

      <label class="form-label mt-3">メールアドレス</label>
      <input type="email" name="email" class="form-control" placeholder="例：test@example.com" value="{{ old('email') }}">

      <label class="form-label mt-3">パスワード</label>
      <input type="password" name="password" class="form-control" placeholder="例：coachtecht06">

      <button class="btn btn-dark w-25 mt-4">登録</button>
    </form>
  </div>
@endsection
