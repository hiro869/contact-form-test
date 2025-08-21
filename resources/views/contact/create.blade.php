@extends('layouts.app')

@section('content')
    <h2>お問い合わせフォーム</h2>

    <form method="POST" action="{{ route('contact.store') }}">
        @csrf
        <div>
            <label>お名前</label>
            <input type="text" name="name" value="{{ old('name', $defaults['name'] ?? '') }}">
        </div>

        <div>
            <label>性別</label>
            <select name="gender">
                <option value="">選択してください</option>
                <option value="男性">男性</option>
                <option value="女性">女性</option>
            </select>
        </div>

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email', $defaults['email'] ?? '') }}">
        </div>

        <button type="submit">送信</button>
    </form>
@endsection
