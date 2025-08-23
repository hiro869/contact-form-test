@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
<div class="page contact">
  <h2 class="page-title">Contact</h2>

  <form id="contactForm" method="POST" action="{{ route('contact.confirm') }}" class="form" novalidate>
    @csrf

    {{-- お名前 --}}
    <label class="req">お名前</label>
    <div class="field two-cols">
      <div>
        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="例：山田" class="ipt">
        @error('last_name') <p class="err">{{ $message }}</p> @enderror
      </div>
      <div>
        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="例：太郎" class="ipt">
        @error('first_name') <p class="err">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- 性別 --}}
    <label class="req">性別</label>
    <div class="field radios">
      <label><input type="radio" name="gender" value="1" {{ old('gender',1)==1?'checked':'' }}> 男性</label>
      <label><input type="radio" name="gender" value="2" {{ old('gender')==2?'checked':'' }}> 女性</label>
      <label><input type="radio" name="gender" value="3" {{ old('gender')==3?'checked':'' }}> その他</label>
      @error('gender') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- メール --}}
    <label class="req">メールアドレス</label>
    <div class="field">
      <input type="email" name="email" value="{{ old('email') }}" placeholder="例：test@example.com" class="ipt">
      @error('email') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- 電話番号（3分割 + hidden結合） --}}
    @php
      $oldTel = preg_replace('/\D/','', old('tel',''));
      $t1 = $oldTel ? substr($oldTel, 0, 3) : old('tel1');
      $t2 = $oldTel ? substr($oldTel, 3, 4) : old('tel2');
      $t3 = $oldTel ? substr($oldTel, 7)     : old('tel3');
    @endphp
    <label class="req">電話番号</label>
    <div class="field tel">
      <input type="text" id="tel1" value="{{ $t1 }}" inputmode="numeric" maxlength="4" placeholder="080"  class="ipt ipt-tel">
      <span class="sep">-</span>
      <input type="text" id="tel2" value="{{ $t2 }}" inputmode="numeric" maxlength="4" placeholder="1234" class="ipt ipt-tel">
      <span class="sep">-</span>
      <input type="text" id="tel3" value="{{ $t3 }}" inputmode="numeric" maxlength="4" placeholder="5678" class="ipt ipt-tel">
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

    {{-- 種類 --}}
    <label class="req">お問い合わせの種類</label>
    <div class="field field--narrow">
      <select name="category_id" class="ipt select js-select" required>
        <option value="" disabled {{ old('category_id') ? '' : 'selected' }} hidden>選択してください</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ (string)old('category_id')===(string)$cat->id ? 'selected' : '' }}>
            {{ $cat->content }}
          </option>
        @endforeach
      </select>
      @error('category_id') <p class="err">{{ $message }}</p> @enderror
    </div>

    {{-- 内容 --}}
    <label class="req">お問い合わせ内容</label>
    <div class="field">
      <textarea name="detail" rows="6" class="ipt area" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
      @error('detail') <p class="err">{{ $message }}</p> @enderror
    </div>

    <div class="form-actions">
      <button type="submit" class="btn primary">確認画面</button>
    </div>
  </form>
</div>

@push('scripts')
<script>
(function(){
  const f = document.getElementById('contactForm');

  // 送信時に3分割を結合して hidden[name=tel] へ
  f.addEventListener('submit', function(){
    const num = id => (document.getElementById(id).value || '').replace(/\D/g,'');
    document.getElementById('tel').value = [num('tel1'), num('tel2'), num('tel3')].join('');
  });

  // セレクトの薄色制御（未選択時）
  document.querySelectorAll('.js-select').forEach(function(s){
    const paint = () => s.classList.toggle('is-placeholder', !s.value);
    paint();
    s.addEventListener('change', paint);
  });
})();
</script>
@endpush
@endsection
