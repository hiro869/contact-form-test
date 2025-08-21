@extends('layouts.app')

@section('content')
<h2 class="page-title">確認</h2>

<form method="POST" action="{{ route('contact.store') }}" class="confirm-form">
  @csrf

  {{-- 表示 --}}
  <dl class="kv">
    <dt>お名前</dt><dd>{{ $data['last_name'] }}　{{ $data['first_name'] }}</dd>
    <dt>性別</dt><dd>{{ $data['gender_label'] }}</dd>
    <dt>メールアドレス</dt><dd>{{ $data['email'] }}</dd>
    <dt>電話番号</dt><dd>{{ $data['tel'] }}</dd>
    <dt>住所</dt><dd>{{ $data['address'] }}</dd>
    <dt>建物名</dt><dd>{{ $data['building'] ?? '（なし）' }}</dd>
    <dt>お問い合わせの種類</dt><dd>{{ $data['category_label'] }}</dd>
    <dt>お問い合わせ内容</dt><dd>{{ $data['content'] }}</dd>
  </dl>

  {{-- hidden で全値を送る --}}
  @foreach($data as $k=>$v)
    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
  @endforeach

  <div class="submit-row">
    <button type="submit" name="back" class="btn ghost">戻る</button>
    <button type="submit" class="btn primary">送信</button>
  </div>
</form>
@endsection
