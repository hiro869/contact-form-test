<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    /* =========================
        フロント：入力 → 確認 → 保存
       ========================= */

    // 入力
    public function create()
    {
        $categories = Category::orderBy('id')->get();
        return view('contact.create', compact('categories'));
    }

    // 確認
    public function confirm(ContactRequest $request)
    {
        // バリデーション済みの実データ
        $inputs = $request->validated();

        // 念のため数字以外を除去
        $inputs['tel'] = preg_replace('/\D/', '', $inputs['tel'] ?? '');

        // “修正”で戻れるように session に保持
        session(['contact_inputs' => $inputs]);

        $category = Category::find($inputs['category_id']);

        return view('contact.confirm', compact('inputs', 'category'));
    }

    // 保存（送信）
    public function store(Request $request)
    {
        // 確認画面経由のデータのみ許可
        $payload = session('contact_inputs');
        abort_if(empty($payload), 419);

        Contact::create($payload);

        // 使い終わったので破棄
        session()->forget('contact_inputs');

        return redirect()->route('contact.thanks');
    }

    // 修正（入力へ戻る：old() に値を積む）
    public function back(Request $request)
    {
        return redirect()
            ->route('contact.create')
            ->withInput($request->except('_token'));
    }

    /* =========================
        管理画面
       ========================= */

    // 一覧（検索・ページネーション）
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

    // 詳細（モーダルの中身だけ返す想定）
    public function show(Contact $contact)
    {
        $contact->load('category');

        if (request()->ajax()) {
            return view('admin.show', compact('contact'));
        }
        abort(404);
    }

    // CSV エクスポート（現在の絞り込み条件のまま）
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

    // 削除（AJAX/通常遷移 両対応）
    public function destroy(Contact $contact)
    {
        $contact->delete();

        if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->noContent(); // 204
        }
        return back()->with('status', '削除しました');
    }

    /* =========================
        一覧/CSV 共通の検索条件
       ========================= */
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
