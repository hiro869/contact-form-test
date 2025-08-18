<h1>お問い合わせ入力</h1>

<form method="POST" action="{{ route('contact.confirm') }}">
  @csrf

  <div>
    <label>姓</label>
    <input name="last_name" value="{{ old('last_name', $defaults['last_name'] ?? '') }}">
    @error('last_name') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>名</label>
    <input name="first_name" value="{{ old('first_name', $defaults['first_name'] ?? '') }}">
    @error('first_name') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>性別</label>
    <select name="gender">
      <option value="1" @selected(old('gender', $defaults['gender'] ?? '1')=='1')>男性</option>
      <option value="2" @selected(old('gender', $defaults['gender'] ?? '')=='2')>女性</option>
      <option value="3" @selected(old('gender', $defaults['gender'] ?? '')=='3')>その他</option>
    </select>
    @error('gender') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>メール</label>
    <input name="email" value="{{ old('email', $defaults['email'] ?? '') }}">
    @error('email') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>電話番号（ハイフンなし）</label>
    <input name="tel" value="{{ old('tel', $defaults['tel'] ?? '') }}">
    @error('tel') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>住所</label>
    <input name="address" value="{{ old('address', $defaults['address'] ?? '') }}">
    @error('address') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>建物名</label>
    <input name="building" value="{{ old('building', $defaults['building'] ?? '') }}">
    @error('building') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>お問い合わせの種類</label>
    <select name="category_id">
      <option value="">選択してください</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" @selected(old('category_id', $defaults['category_id'] ?? '')==$cat->id)>
          {{ $cat->content }}
        </option>
      @endforeach
    </select>
    @error('category_id') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>お問い合わせ内容（120字以内）</label>
    <textarea name="detail">{{ old('detail', $defaults['detail'] ?? '') }}</textarea>
    @error('detail') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <button type="submit">確認画面</button>
</form>
