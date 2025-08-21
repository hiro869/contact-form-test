@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('content')
<div class="admin-wrap">

  <h2 class="page-title">Admin</h2>

  {{-- ===== 検索フォーム → ページネーション（この順で固定） ===== --}}
  <div class="search-area">
    <form method="GET" action="{{ route('admin.index') }}" class="search-row">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="名前やメールアドレスを入力してください">

      @php $g = request()->has('gender') ? (string)request('gender') : null; @endphp
      <select name="gender">
        <option value="" disabled {{ $g===null ? 'selected' : '' }}>性別</option>
        <option value="all" {{ $g==='all' ? 'selected' : '' }}>全て</option>
        <option value="1"   {{ $g==='1'   ? 'selected' : '' }}>男性</option>
        <option value="2"   {{ $g==='2'   ? 'selected' : '' }}>女性</option>
        <option value="3"   {{ $g==='3'   ? 'selected' : '' }}>その他</option>
      </select>

      <select name="category_id">
        <option value="">お問い合わせの種類</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ (string)request('category_id')===(string)$cat->id ? 'selected' : '' }}>
            {{ $cat->content }}
          </option>
        @endforeach
      </select>

      <input type="date" name="date" value="{{ request('date') }}">

      <div class="actions">
        <button type="submit" class="btn primary">検索</button>
        <a href="{{ route('admin.index') }}" class="btn ghost">リセット</a>
      </div>
    </form>

    {{-- ★検索/リセットの直下にだけ表示（他の links(...) はプロジェクトから削除） --}}
    <div class="pager-row">
      {{ $contacts->withQueryString()->onEachSide(1)->links('vendor.pagination.admin') }}
    </div>
  </div>

  {{-- エクスポート --}}
  <div class="export-row">
    <a class="btn ghost wide" href="{{ route('admin.export', request()->query()) }}">エクスポート</a>
  </div>

  {{-- ===== 一覧 ===== --}}
  <div class="table-box">
    <table class="table">
      <thead>
        <tr>
          <th>お名前</th>
          <th>性別</th>
          <th>メールアドレス</th>
          <th>お問い合わせの種類</th>
          <th class="th-detail"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($contacts as $c)
          <tr>
            <td>{{ $c->last_name }}　{{ $c->first_name }}</td>
            <td>{{ ['','男性','女性','その他'][$c->gender ?? 0] }}</td>
            <td>{{ $c->email }}</td>
            <td class="nowrap">{{ optional($c->category)->content }}</td>
            <td class="cell-actions">
              <button type="button"
                class="btn-detail"
                data-id="{{ $c->id }}"
                data-name="{{ $c->last_name }}　{{ $c->first_name }}"
                data-gender="{{ ['','男性','女性','その他'][$c->gender ?? 0] }}"
                data-email="{{ $c->email }}"
                data-tel="{{ $c->tel ?? '' }}"
                data-address="{{ $c->address ?? '' }}"
                data-building="{{ $c->building ?? '' }}"
                data-category="{{ optional($c->category)->content ?? '' }}"
                data-content="{{ $c->detail ?? ($c->content ?? '') }}"
              >詳細</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="empty">該当データがありません</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

{{-- ===== 詳細モーダル（ヘッダーなし/区切り線なし/×で閉じる/削除） ===== --}}
<div id="detailModal" class="modal">
  <div class="modal__overlay" data-close="1"></div>
  <div class="modal__panel" role="dialog" aria-modal="true">
    <button class="modal__close" type="button" aria-label="閉じる" data-close="1">×</button>

    <div class="modal__body">
      <dl class="kv"><dt>お名前</dt><dd id="m-name"></dd></dl>
      <dl class="kv"><dt>性別</dt><dd id="m-gender"></dd></dl>
      <dl class="kv"><dt>メールアドレス</dt><dd id="m-email"></dd></dl>
      <dl class="kv"><dt>電話番号</dt><dd id="m-tel"></dd></dl>
      <dl class="kv"><dt>住所</dt><dd id="m-address"></dd></dl>
      <dl class="kv"><dt>建物名</dt><dd id="m-building"></dd></dl>
      <dl class="kv"><dt>お問い合わせの種類</dt><dd id="m-category" class="nowrap"></dd></dl>
      <dl class="kv"><dt>お問い合わせ内容</dt><dd id="m-content" class="prewrap"></dd></dl>
    </div>

    <form id="deleteForm" method="POST" action="">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn danger center">削除</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const modal = document.getElementById('detailModal');
  const delForm = document.getElementById('deleteForm');

  function openModal(data){
    document.getElementById('m-name').textContent    = data.name || '';
    document.getElementById('m-gender').textContent  = data.gender || '';
    document.getElementById('m-email').textContent   = data.email || '';
    document.getElementById('m-tel').textContent     = data.tel || '';
    document.getElementById('m-address').textContent = data.address || '';
    document.getElementById('m-building').textContent= data.building || '';
    document.getElementById('m-category').textContent= data.category || '';
    document.getElementById('m-content').textContent = data.content || '';

    // 名前付きルートで確実に DELETE 先を生成（web.php に admin.destroy が必要）
    delForm.action = "{{ route('admin.destroy', ':id') }}".replace(':id', data.id);

    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function closeModal(){
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  // 詳細/閉じる イベント委譲
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-detail');
    if(btn){
      openModal({
        id:btn.dataset.id,
        name:btn.dataset.name,
        gender:btn.dataset.gender,
        email:btn.dataset.email,
        tel:btn.dataset.tel,
        address:btn.dataset.address,
        building:btn.dataset.building,
        category:btn.dataset.category,
        content:btn.dataset.content
      });
    }
    if(e.target.closest('[data-close]') || e.target.classList.contains('modal__overlay')) closeModal();
  });

  window.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });
})();
</script>
@endpush
