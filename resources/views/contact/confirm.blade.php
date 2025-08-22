@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}?v={{ filemtime(public_path('css/confirm.css')) }}">
@endpush

@section('content')
<div class="confirm-wrap">
  <h2 class="page-title">Confirm</h2>

  <table class="kv">
    <tr><th>お名前</th><td>{{ $data['last_name'] }}　{{ $data['first_name'] }}</td></tr>
    <tr><th>性別</th><td>{{ $data['gender_label'] }}</td></tr>
    <tr><th>メールアドレス</th><td>{{ $data['email'] }}</td></tr>
    <tr><th>電話番号</th><td>{{ $data['tel'] }}</td></tr>
    <tr><th>住所</th><td>{{ $data['address'] }}</td></tr>
    <tr><th>建物名</th><td>{{ $data['building'] ?: '（なし）' }}</td></tr>
    <tr><th>お問い合わせの種類</th><td>{{ $data['category_label'] }}</td></tr>
    <tr><th>お問い合わせ内容</th><td>{{ $data['content'] }}</td></tr>
  </table>

  <form method="POST" action="{{ route('contact.store') }}" class="actions">
    @csrf
    @foreach($data as $k => $v)
      <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach
    <button type="submit" class="btn primary">送信</button>
    <a href="{{ route('contact.create') }}" class="btn ghost">修正</a>
  </form>
</div>
@endsection
