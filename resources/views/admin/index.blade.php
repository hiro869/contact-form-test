<h1>管理画面</h1>

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
  <a href="{{ route('admin.index') }}" style="margin-left:8px;">リセット</a>
</form>

<form method="GET" action="{{ route('admin.export') }}" style="margin-bottom:12px;">
  {{-- 絞り込みを維持したままエクスポート --}}
  <input type="hidden" name="q" value="{{ request('q') }}">
  <input type="hidden" name="gender" value="{{ request('gender') }}">
  <input type="hidden" name="category_id" value="{{ request('category_id') }}">
  <input type="hidden" name="date" value="{{ request('date') }}">
  <button type="submit">エクスポート</button>
</form>

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
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->last_name }}　{{ $c->first_name }}</td>
        <td>{{ ['','男性','女性','その他'][$c->gender] ?? '' }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ optional($c->category)->content }}</td>
        <td>
          <button type="button"
                  onclick="openDetail({{ htmlspecialchars(json_encode([
                      'name'=>$c->last_name.' '.$c->first_name,
                      'gender'=>['','男性','女性','その他'][$c->gender] ?? '',
                      'email'=>$c->email,
                      'tel'=>$c->tel,
                      'address'=>$c->address,
                      'building'=>$c->building,
                      'category'=>optional($c->category)->content,
                      'detail'=>$c->detail,
                  ]), ENT_QUOTES, 'UTF-8') }})">
            詳細
          </button>

          <form method="POST" action="{{ route('admin.destroy',$c) }}" style="display:inline"
                onsubmit="return confirm('削除してよろしいですか？')">
            @csrf @method('DELETE')
            <button type="submit">削除</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="6">該当データがありません</td></tr>
    @endforelse
  </tbody>
</table>

<div style="margin-top:12px;">
  {{ $contacts->withQueryString()->links() }}
</div>

{{-- モーダル（簡易） --}}
<dialog id="detailModal">
  <h3>詳細</h3>
  <div id="detailBody" style="white-space:pre-wrap"></div>
  <div style="margin-top:12px; text-align:right;">
    <button onclick="document.getElementById('detailModal').close()">閉じる</button>
  </div>
</dialog>

<script>
function openDetail(data){
  const b = document.getElementById('detailBody');
  b.textContent =
    `お名前：${data.name}
性別：${data.gender}
メール：${data.email}
電話番号：${data.tel ?? ''}
住所：${data.address ?? ''}
建物名：${data.building ?? ''}
お問い合わせの種類：${data.category ?? ''}
お問い合わせ内容：${data.detail ?? ''}`;
  document.getElementById('detailModal').showModal();
}
</script>

<p style="margin-top:20px;"><a href="{{ route('contact.create') }}">HOME（入力へ）</a></p>
