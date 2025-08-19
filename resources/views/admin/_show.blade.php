<div>
  <table class="detail-table">
    <tr><th>お名前</th><td>{{ $contact->last_name }}　{{ $contact->first_name }}</td></tr>
    <tr><th>性別</th>
      <td>
        @if($contact->gender==1) 男性
        @elseif($contact->gender==2) 女性
        @else その他
        @endif
      </td>
    </tr>
    <tr><th>メールアドレス</th><td>{{ $contact->email }}</td></tr>
    <tr><th>電話番号</th><td>{{ $contact->tel }}</td></tr>
    <tr><th>住所</th><td>{{ $contact->address }}</td></tr>
    <tr><th>建物名</th><td>{{ $contact->building }}</td></tr>
    <tr><th>お問い合わせの種類</th><td>{{ optional($contact->category)->content }}</td></tr>
    <tr><th>お問い合わせ内容</th><td style="white-space:pre-wrap;">{{ $contact->detail }}</td></tr>
  </table>

  <div class="detail-actions">
    <button type="button" class="btn-danger js-delete" data-id="{{ $contact->id }}">削除</button>
  </div>
</div>
