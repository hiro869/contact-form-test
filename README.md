# お問い合わせフォーム（確認テスト）
## 環境構築
1.準備
composer create-project laravel/laravel contact-form
cd contact-form

2.Github リポジトリ作成
git init
git add .
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/hiro869/contact-form-test.git
git push -u origin main

3.laravel Sail　インストール
php artisan sail:install
->mysqlを選択

4.Dockerコンテナ起動
./vendor/bin/sail up -d
確認
./vendor/bin/sail ps
停止再起動
./vendor/bin/sail down
./vendor/bin/sail up -d

5.mysqlコンテナ起動確認
./vendor/bin/sail logs mysql --tail=100
問題がある場合はボリュームを削除して再作成
./vendor/bin/sail down -v
./vendor/bin/sail up -d

6.laravelセットアップ
.env設定例
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=【ユーザー名】
DB_PASSWORD=【パスワード】

キャッシュクリア
./vendor/bin/sail artisan config:clear
キー生成
./vendor/bin/sail artisan key:generate
7.マイグレーション＆シーディング
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

使用技術

PHP 8.4
Laravel 12.x
MySQL 8.0
Docker / Laravel Sail

## ER図

## URL

## 補足
課題の指示では「電話番号は5桁まで」と記載されていましたが,
一般的な日本の電話番号仕様（固定電話は10桁、携帯電話は11桁）に合わせ、
本実装では **10〜11桁の数字** を受け付ける仕様としました。

※もし「5桁まで」という指示が「入力欄ごと（3分割のそれぞれが最大5桁）」という意味であれば、
`digits_between` ではなく `max:5` を各分割入力に適用する形に変更可能です。
