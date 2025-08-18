<h1>確認</h1>

<p>お名前：{{ $inputs['last_name'] }} {{ $inputs['first_name'] }}</p>
<p>性別：
  @if($inputs['gender']==1) 男性
  @elseif($inputs['gender']==2) 女性
  @else その他
  @endif
</p>
<p>メール：{{ $inputs['email'] }}</p>
<p>電話番号：{{ $inputs['tel'] }}</p>
<p>住所：{{ $inputs['address'] }}</p>
<p>建物名：{{ $inputs['building'] }}</p>
<p>お問い合わせ種別：{{ \App\Models\Category::find($inputs['category_id'])->content ?? '-' }}</p>
<p>お問い合わせ内容：{{ $inputs['detail'] }}</p>

{{-- 戻る --}}
<form method="GET" action="{{ route('contact.create') }}" style="display:inline">
  <button>修正</button>
</form>

{{-- 送信（ここがポイント：hidden で全項目をPOST） --}}
<form method="POST" action="{{ route('contact.store') }}" style="display:inline">
  @csrf
  <input type="hidden" name="last_name"   value="{{ $inputs['last_name'] }}">
  <input type="hidden" name="first_name"  value="{{ $inputs['first_name'] }}">
  <input type="hidden" name="gender"      value="{{ $inputs['gender'] }}">
  <input type="hidden" name="email"       value="{{ $inputs['email'] }}">
  <input type="hidden" name="tel"         value="{{ $inputs['tel'] }}">
  <input type="hidden" name="address"     value="{{ $inputs['address'] }}">
  <input type="hidden" name="building"    value="{{ $inputs['building'] }}">
  <input type="hidden" name="category_id" value="{{ $inputs['category_id'] }}">
  <input type="hidden" name="detail"      value="{{ $inputs['detail'] }}">
  <button type="submit">送信</button>
</form>
