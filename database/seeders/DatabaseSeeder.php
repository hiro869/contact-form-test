<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // カテゴリ5件（仕様）
    \App\Models\Category::query()->delete();
    \App\Models\Category::insert([
        ['content'=>'商品の お届けについて','created_at'=>now(),'updated_at'=>now()],
        ['content'=>'商品の 交換について','created_at'=>now(),'updated_at'=>now()],
        ['content'=>'商品トラブル','created_at'=>now(),'updated_at'=>now()],
        ['content'=>'ショップへのお問い合わせ','created_at'=>now(),'updated_at'=>now()],
        ['content'=>'その他','created_at'=>now(),'updated_at'=>now()],
    ]);

    // お問い合わせ35件
    \App\Models\Contact::factory()->count(35)->create();
}

}
