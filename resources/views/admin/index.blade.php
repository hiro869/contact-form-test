<h1>管理画面</h1>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.index') }}" style="margin-bottom: 16px;">
  <input type="text" name="q" placeholder="名前やメールアドレスを入力してください" value="{{ request('q') }}">
  <select name="gender">
    <option value="">性別</option>
    <option value="1" @selected(request('gender')==='1')>男性</option>
    <option value="2" @selected(request('gender')==='2')>女性</option>
    <option value="3" @selected(request('gender')==='3')>その他</option>
  </select>
  <select name="category_id">
    <option value="">お問い合わせの種類</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->content }}</option>
    @endforeach
  </select>
  <input type="date" name="date" value="{{ request('date') }}">
  <button>検索</button>
  <div class="toolbar" style="display:flex; justify-content:space-between; align-items:center; margin:8px 0 12px;">
  {{-- ここにエクスポートや検索フォームがあるなら左側に置く --}}
  <div></div>

  {{-- ページネーション --}}
  <div class="pager">
    {{ $contacts->withQueryString()->onEachSide(1)->links() }}
  </div>
</div>
  <style>
/* ▼ Laravelのpaginationブロック全体を中央配置に */
nav[role="navigation"]{
  display:flex;
  justify-content:center;   /* 中央寄せ */
  align-items:center;
  margin: 16px 0 24px;
  gap: 12px;
}

/* 「Showing 8 to 14 of 33 results」などの説明行を消す */
nav[role="navigation"] > div:first-child{
  display:none;
}

/* ul を横並びにして箇条書きの点を消す */
nav[role="navigation"] ul{
  display:inline-flex;
  gap:6px;
  list-style:none;
  padding:0;
  margin:0;
}

/* ページ番号の見た目を簡単に整える（必要なら調整してOK） */
nav[role="navigation"] a,
nav[role="navigation"] span{
  display:inline-block;
  padding:6px 10px;
  border:1px solid #ddd;
  border-radius:4px;
  text-decoration:none;
}

/* 現在ページを反転 */
nav[role="navigation"] span[aria-current="page"]{
  background:#333; color:#fff; border-color:#333;
}

/* ← → のSVG矢印が巨大化している環境向けの保険 */
nav[role="navigation"] svg{
  width: 1em; height: 1em;   /* 文字サイズ相当に縮める */
  vertical-align: -0.125em;
}
</style>
  <a href="{{ route('admin.index') }}" style="margin-left:8px;">リセット</a>
</form>

{{-- エクスポート（絞り込み維持） --}}
<form method="GET" action="{{ route('admin.export') }}" style="margin-bottom:12px;">
  <input type="hidden" name="q" value="{{ request('q') }}">
  <input type="hidden" name="gender" value="{{ request('gender') }}">
  <input type="hidden" name="category_id" value="{{ request('category_id') }}">
  <input type="hidden" name="date" value="{{ request('date') }}">
  <button type="submit">エクスポート</button>
</form>

{{-- 一覧テーブル --}}
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>お名前</th>
      <th>性別</th>
      <th>メールアドレス</th>
      <th>お問い合わせの種類</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    @forelse($contacts as $c)
      <tr data-row-id="contact-{{ $c->id }}">
        <td>{{ $c->id }}</td>
        <td>{{ $c->last_name }}　{{ $c->first_name }}</td>
        <td>{{ ['','男性','女性','その他'][$c->gender] ?? '' }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ optional($c->category)->content }}</td>
        <td>
          {{-- ★ 詳細ボタン（AJAXでモーダル表示）に統一 --}}
          <button type="button" class="btn js-detail" data-id="{{ $c->id }}">詳細</button>
        </td>
      </tr>
    @empty
      <tr><td colspan="6">該当データがありません</td></tr>
    @endforelse
  </tbody>
</table>
{{-- ===================== モーダル枠 + JS（末尾に配置） ===================== --}}

{{-- ※ レイアウトでcsrfメタを出していない場合のみ置く --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- モーダル外枠 --}}
<div id="detailModal" class="modal hidden">
  <div class="modal-panel">
    <button id="modalX" type="button" class="modal-close" aria-label="close">✕</button>
    <div id="modalBody">読み込み中...</div>
  </div>
</div>

<style>
.modal.hidden{ display:none; }
.modal{ position:fixed; inset:0; background:rgba(0,0,0,.35); display:flex; align-items:center; justify-content:center; z-index:1000;}
.modal-panel{ background:#fff; width:min(800px,92%); max-height:85vh; overflow:auto; border-radius:10px; padding:28px 32px; position:relative; box-shadow:0 10px 30px rgba(0,0,0,.2);}
.modal-close{ position:absolute; right:14px; top:12px; border:none; background:#fff; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; }
.modal-close:hover{ background:#f5f5f5; }
.btn{ padding:.4rem .8rem; border:1px solid #ccc; background:#fff; cursor:pointer; }
.btn-danger{ background:#b84a3a; color:#fff; border:none; padding:.55rem 1.2rem; border-radius:4px; }
.detail-table{ width:100%; border-collapse:separate; border-spacing:0 14px; }
.detail-table th{ width:28%; color:#86735e; text-align:left; }
.detail-table td{ color:#433; }
.detail-actions{ text-align:center; margin-top:24px; }
</style>

<script>
const modal     = document.getElementById('detailModal');
const modalBody = document.getElementById('modalBody');
const csrf      = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

// 詳細（.js-detail）クリックで部分ビューを読んで差し込み
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.js-detail');
  if (!btn) return;

  const id  = btn.dataset.id;
  const url = "{{ route('admin.show', ':id') }}".replace(':id', id);

  modalBody.textContent = '読み込み中...';
  modal.classList.remove('hidden');

  try {
    const res  = await fetch(url, { headers:{ 'X-Requested-With':'XMLHttpRequest' } });
    const html = await res.text();
    modalBody.innerHTML = html;
  } catch (err) {
    modalBody.innerHTML = '<p style="color:red;">読み込みに失敗しました。</p>';
  }
});

// モーダルを閉じる（✕ or 背景）
modal.addEventListener('click', (e)=>{
  if (e.target.id === 'modalX' || e.target.id === 'detailModal') {
    modal.classList.add('hidden');
  }
});

// モーダル内の削除ボタン（動的要素なのでデリゲート）
modal.addEventListener('click', async (e)=>{
  const del = e.target.closest('.js-delete');
  if (!del) return;

  if(!confirm('このお問い合わせを削除します。よろしいですか？')) return;

  const id  = del.dataset.id;
  const url = "{{ route('admin.destroy', ':id') }}".replace(':id', id);

  try{
    const res = await fetch(url, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    if (res.ok || res.status === 204) {
      // 行を削除
      const row = document.querySelector(`[data-row-id="contact-${id}"]`);
      if (row) row.remove();
      modal.classList.add('hidden');
    } else {
      alert('削除に失敗しました');
    }
  }catch(err){
    alert('削除に失敗しました');
  }
});
</script>


