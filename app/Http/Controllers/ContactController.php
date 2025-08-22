<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    // ===== お問い合わせ：入力 =====
    public function create()
    {
        // 戻る時用の値保持
        $defaults   = session('contact_inputs', []);
        $categories = Category::orderBy('id')->get();

        return view('contact.create', compact('defaults', 'categories'));
    }

// app/Http/Controllers/ContactController.php

public function confirm(ContactRequest $request)
{
    $inputs = $request->validated();           // ← 実データ（保存用）
    session(['contact_inputs' => $inputs]);    // ← 戻る用にも保持

    // 表示ラベルだけ作る（画面表示専用）
    $genderMap = [1=>'男性', 2=>'女性', 3=>'その他'];
    $category  = \App\Models\Category::find($inputs['category_id']);

    $viewData = [
        'last_name'      => $inputs['last_name'],
        'first_name'     => $inputs['first_name'],
        'gender_label'   => $genderMap[$inputs['gender']] ?? '',
        'email'          => $inputs['email'],
        'tel'            => $inputs['tel'],
        'address'        => $inputs['address'],
        'building'       => $inputs['building'] ?? '',
        'category_label' => $category->content ?? '',
        'content'        => $inputs['detail'],   // 表示用は content
    ];

    // ← 画面には「表示用(viewData)」と「保存用(inputs)」の両方を渡す
    return view('contact.confirm', [
        'data'   => $viewData,
        'inputs' => $inputs,
    ]);
}

public function store(Request $request)
{
    // 確認画面からは session に入れておいた “保存用の実データ” を使う
    $payload = session('contact_inputs');

    abort_if(empty($payload), 419); // 直接叩かれた等の安全策

    \App\Models\Contact::create($payload);     // ← $fillable を用意しておく
    session()->forget('contact_inputs');

    // 二重送信防止でリダイレクト
    return redirect()->route('contact.thanks');
}
    // ===== 管理一覧（検索 + ページネーション + 7件/ページ） =====
    public function admin(Request $request)
    {
        $categories = Category::orderBy('id')->get();

        $contacts = $this->adminQuery($request)
            ->with('category')
            ->latest('id')
            ->paginate(7)
            ->appends($request->query()); // 検索条件をページ送りに引き継ぐ

        return view('admin.index', compact('contacts', 'categories', 'request'));
    }

    // ===== 詳細（モーダル用：中身だけ返す） =====
    public function show(Contact $contact)
    {
        $contact->load('category');

        if (request()->ajax()) {
            return view('admin.show', compact('contact'));
        }
        abort(404);
    }

    // ===== CSVエクスポート（絞り込み状態のまま出力） =====
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->adminQuery($request)
            ->with('category')
            ->latest('id')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=SJIS-win',
            'Content-Disposition' => 'attachment; filename="contacts.csv"',
        ];

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output', 'w');

            $put = function (array $row) use ($out) {
                mb_convert_variables('SJIS-win', 'UTF-8', $row);
                fputcsv($out, $row);
            };

            // ヘッダ
            $put(['ID', '氏', '名', '性別', 'メール', '種類', '作成日']);

            foreach ($rows as $c) {
                $put([
                    $c->id,
                    $c->last_name,
                    $c->first_name,
                    ['', '男性', '女性', 'その他'][$c->gender] ?? '',
                    $c->email,
                    optional($c->category)->content,
                    optional($c->created_at)?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }

    // ===== 削除（モーダル内の削除ボタンからのAJAX/通常遷移の両対応） =====
    public function destroy(Contact $contact)
    {
        $contact->delete();

        if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->noContent(); // 204
        }

        return back()->with('status', '削除しました');
    }

    // ===== 管理画面の検索条件（共通化：一覧/CSVで使い回し） =====
    private function adminQuery(Request $request)
    {
        $q          = $request->input('q');            // 氏名/メール（部分一致・フルネーム結合にも対応）
        $gender     = $request->input('gender');       // 1/2/3
        $categoryId = $request->input('category_id');  // 種別
        $date       = $request->input('date');         // yyyy-mm-dd（created_at）

        $query = Contact::query();

        if (filled($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('last_name', 'like', "%{$q}%")
                  ->orWhere('first_name', 'like', "%{$q}%")
                  ->orWhereRaw("concat(last_name,' ',first_name) like ?", ["%{$q}%"])
                  ->orWhereRaw("concat(last_name,first_name) like ?", ["%{$q}%"])
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if (in_array($gender, ['1', '2', '3'], true)) {
            $query->where('gender', $gender);
        }

        if (filled($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (filled($date)) {
            $query->whereDate('created_at', $date);
        }

        return $query;
    }
}
