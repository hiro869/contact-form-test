@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@section('content')
<div class="page contact">
  <h2 class="page-title">Contact</h2>

  <form id="contactForm" method="POST" action="{{ route('contact.confirm') }}" class="form">
    @csrf

    {{-- お名前 --}}
    <label class="req">お名前</label>
    <div class="field two-cols">
      <input type="text" name="last_name"  value="{{ old('last_name') }}"  placeholder="例：山田"  class="ipt">
      <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="例：太郎"  class="ipt">
      @error('last_name') <p class="err">{{ $message }}</p> @enderror
      @error('first_name') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- 性別 --}}
    <label class="req">性別</label>
    <div class="field radios">
      <label><input type="radio" name="gender" value="1" {{ old('gender',1)==1?'checked':'' }}> 男性</label>
      <label><input type="radio" name="gender" value="2" {{ old('gender')==2?'checked':'' }}> 女性</label>
      <label><input type="radio" name="gender" value="3" {{ old('gender')==3?'checked':'' }}> その他</label>
      @error('gender') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- メールアドレス --}}
    <label class="req">メールアドレス</label>
    <div class="field">
      <input type="email" name="email" value="{{ old('email') }}" placeholder="例：test@example.com" class="ipt">
      @error('email') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- 電話番号（3分割 + hidden結合） --}}
    <label class="req">電話番号</label>
    <div class="field tel">
      <input type="text" id="tel1" inputmode="numeric" pattern="\d*" maxlength="4"  placeholder="080"  class="ipt ipt-tel">
      <span class="sep">-</span>
      <input type="text" id="tel2" inputmode="numeric" pattern="\d*" maxlength="4"  placeholder="1234" class="ipt ipt-tel">
      <span class="sep">-</span>
      <input type="text" id="tel3" inputmode="numeric" pattern="\d*" maxlength="4"  placeholder="5678" class="ipt ipt-tel">
      <input type="hidden" name="tel" id="tel">
      @error('tel') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- 住所 --}}
    <label class="req">住所</label>
    <div class="field">
      <input type="text" name="address" value="{{ old('address') }}" placeholder="例：東京都渋谷区千駄ヶ谷1-2-3" class="ipt">
      @error('address') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- 建物名（任意） --}}
    <label>建物名</label>
    <div class="field">
      <input type="text" name="building" value="{{ old('building') }}" placeholder="例：千駄ヶ谷マンション101" class="ipt">
      @error('building') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- お問い合わせの種類 --}}
    <label class="req">お問い合わせの種類</label>
    <div class="field">
      <select name="category_id" class="ipt">
        <option value="" disabled {{ old('category_id')==''?'selected':'' }}>選択してください</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ (string)old('category_id')===(string)$cat->id?'selected':'' }}>
            {{ $cat->content }}
          </option>
        @endforeach
      </select>
      @error('category_id') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- お問い合わせ内容 --}}
    <label class="req">お問い合わせ内容</label>
    <div class="field">
      <textarea name="detail" rows="6" placeholder="お問い合わせ内容をご記載ください" class="ipt area">{{ old('detail') }}</textarea>
      @error('detail') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- ボタン --}}
    <div class="form-actions">
      <button type="submit" class="btn primary">確認画面</button>
    </div>
  </form>
</div>

{{-- 3分割電話番号を hidden #tel に結合 --}}
@push('scripts')
<script>
  (function(){
    const f = document.getElementById('contactForm');
    f.addEventListener('submit', function(){
      const t1 = (document.getElementById('tel1').value || '').replace(/\D/g,'');
      const t2 = (document.getElementById('tel2').value || '').replace(/\D/g,'');
      const t3 = (document.getElementById('tel3').value || '').replace(/\D/g,'');
      document.getElementById('tel').value = [t1,t2,t3].filter(Boolean).join('');
    });
  })();
</script>
@endpush
@endsection
