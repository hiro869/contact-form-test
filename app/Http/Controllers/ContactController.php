<?php


namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    // 入力
    public function create()
    {
        $defaults = session('contact_inputs', []);
        $categories = Category::orderBy('id')->get();
        return view('contact.create', compact('defaults','categories'));
    }

    // 確認
    public function confirm(ContactRequest $request)
    {
        $inputs = $request->validated();
        session(['contact_inputs' => $inputs]);
        return view('contact.confirm', compact('inputs'));
    }

    // 保存→サンクス
    public function store(ContactRequest $request)
    {
        Contact::create($request->validated());
        session()->forget('contact_inputs');
        return view('contact.thanks');
    }

    // ===== 管理画面 =====
    public function admin(Request $request)
    {
        $q          = $request->input('q');                 // 名前/メール
        $gender     = $request->input('gender');            // 1,2,3 or null
        $categoryId = $request->input('category_id');       // カテゴリ
        $date       = $request->input('date');              // YYYY-MM-DD

        $query = Contact::query()->with('category');

        // 1) 名前/メール（部分一致：姓/名/フルネーム/メール）
        if (filled($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('last_name',  'like', "%{$q}%")
                  ->orWhere('first_name','like', "%{$q}%")
                  ->orWhereRaw("concat(last_name,' ',first_name) like ?", ["%{$q}%"])
                  ->orWhereRaw("concat(last_name,first_name) like ?",   ["%{$q}%"])
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        // 3) 性別
        if (in_array($gender, ['1','2','3'], true)) {
            $query->where('gender', $gender);
        }

        // 4) 種類
        if (filled($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // 5) 日付（created_at の日付一致）
        if (filled($date)) {
            $query->whereDate('created_at', $date);
        }

        // 7件ごと
        $contacts = $query->latest('id')->paginate(7)->appends($request->query());

        $categories = Category::orderBy('id')->get();

        return view('admin.index', compact('contacts','categories'));
    }

    // CSVエクスポート（絞り込み状態をそのまま適用）
    public function export(Request $request): StreamedResponse
    {
        // admin() と同じ条件を再利用
        $base = $this->adminQuery($request)->latest('id')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=SJIS-win',
            'Content-Disposition' => 'attachment; filename="contacts.csv"',
        ];

        return response()->stream(function () use ($base) {
            $out = fopen('php://output', 'w');
            // 文字コード：UTF-8→SJIS (Excel想定)
            $put = function(array $row) use ($out) {
                mb_convert_variables('SJIS-win','UTF-8',$row);
                fputcsv($out, $row);
            };
            // ヘッダ
            $put(['ID','氏','名','性別','メール','種類','作成日']);
            foreach ($base as $c) {
                $put([
                    $c->id,
                    $c->last_name,
                    $c->first_name,
                    ['','男性','女性','その他'][$c->gender] ?? '',
                    $c->email,
                    optional($c->category)->content,
                    $c->created_at?->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    // 削除
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('status','削除しました');
    }

    // 条件組み立てだけ共通化（export用）
    private function adminQuery(Request $request)
    {
        $q          = $request->input('q');
        $gender     = $request->input('gender');
        $categoryId = $request->input('category_id');
        $date       = $request->input('date');

        $query = Contact::query()->with('category');

        if (filled($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('last_name',  'like', "%{$q}%")
                  ->orWhere('first_name','like', "%{$q}%")
                  ->orWhereRaw("concat(last_name,' ',first_name) like ?", ["%{$q}%"])
                  ->orWhereRaw("concat(last_name,first_name) like ?",   ["%{$q}%"])
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }
        if (in_array($gender, ['1','2','3'], true)) {
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
