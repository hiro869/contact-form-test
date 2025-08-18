<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // 入力画面
    public function create()
    {
        $defaults  = session('contact_inputs', []);     // 前回入力を保持
        $categories = Category::orderBy('id')->get();   // セレクト用
        return view('contact.create', compact('defaults','categories'));
    }

    // 確認画面
    public function confirm(ContactRequest $request)
    {
        $inputs = $request->validated();                // ここまで来たらバリデ通過
        session(['contact_inputs' => $inputs]);         // 戻ったとき用に保存
        return view('contact.confirm', compact('inputs'));
    }

    // 送信（保存）→ サンクス
    public function store(ContactRequest $request)
    {
        Contact::create($request->validated());         // DB保存
        session()->forget('contact_inputs');            // 入力値を破棄
        return view('contact.thanks');
    }

    // 管理画面（あとで実装）
    public function admin()
    {
        return view('contact.admin');
    }
}
