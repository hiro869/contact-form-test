{{-- resources/views/contact/confirm.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}?v={{ filemtime(public_path('css/confirm.css')) }}">
@endpush

@section('content')
<div class="confirm-wrap">
  <h2 class="page-title">Confirm</h2>

  <table class="kv">
    <tr><th>お名前</th><td>{{ $inputs['last_name'] }}　{{ $inputs['first_name'] }}</td></tr>
    <tr><th>性別</th><td>{{ ['','男性','女性','その他'][$inputs['gender']] ?? '' }}</td></tr>
    <tr><th>メールアドレス</th><td>{{ $inputs['email'] }}</td></tr>
    {{-- 電話番号はハイフン加工せず、そのまま表示（ハイフンなしの10〜11桁） --}}
    <tr><th>電話番号</th><td>{{ $inputs['tel'] }}</td></tr>
    <tr><th>住所</th><td>{{ $inputs['address'] }}</td></tr>
    <tr><th>建物名</th><td>{{ $inputs['building'] ?? '' }}</td></tr>
    <tr><th>お問い合わせの種類</th><td>{{ $category->content ?? '' }}</td></tr>
    <tr><th>お問い合わせ内容</th><td>{{ $inputs['detail'] }}</td></tr>
  </table>

  <div class="actions">
    {{-- 送信 --}}
    <form method="POST" action="{{ route('contact.store') }}">
      @csrf
      @foreach($inputs as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endforeach
      <button type="submit" class="btn primary">送信</button>
    </form>

    {{-- 修正（入力値を持って戻る） --}}
    <form method="POST" action="{{ route('contact.back') }}">
      @csrf
      @foreach($inputs as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endforeach
      <button type="submit" class="btn ghost">修正</button>
    </form>
  </div>
</div>
@endsection
