# お問い合わせフォーム（確認テスト）

## 環境構築
1.プロジェクト作成
composer create-project laravel/laravel contact-form
cd contact-form

2.GitHubリポジトリ作成
git init
git add .
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/hiro869/contact-form-test.git
git push -u origin main

3.Laravel sailインストール
php artisan sail:install
# → mysql を選択

4.Docker コンテナ起動
./vendor/bin/sail up -d

5.MySQL 起動確認
./vendor/bin/sail logs mysql --tail=100

6.Laravel セットアップ
.env 設定
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=xxx
DB_PASSWORD=xxx
7.キャッシュクリア: ./vendor/bin/sail artisan config:clear

キー生成: ./vendor/bin/sail artisan key:generate

マイグレーション & シーディング
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

## 使用技術（実行環境）

PHP 8.2

Laravel 12.x

MySQL 8.0

Docker / Laravel Sail

## ER図
erDiagram
  CATEGORIES ||--o{ CONTACTS : "has many"

  CATEGORIES {
    BIGINT    id PK
    VARCHAR   content
    TIMESTAMP created_at
    TIMESTAMP updated_at
  }

  CONTACTS {
    BIGINT    id PK
    BIGINT    category_id FK
    VARCHAR   first_name
    VARCHAR   last_name
    TINYINT   gender
    VARCHAR   email
    VARCHAR   tel
    VARCHAR   address
    VARCHAR   building
    TEXT      detail
    TIMESTAMP created_at
    TIMESTAMP updated_at
  }

  USERS {
    BIGINT    id PK
    VARCHAR   name
    VARCHAR   email
    VARCHAR   password
    TIMESTAMP created_at
    TIMESTAMP updated_at
  }

  CONTACTS }o--|| CATEGORIES : "belongs to"

## URL

開発環境: http://localhost

補足

課題の指示では「電話番号は5桁まで」とありましたが、
日本の一般的な電話番号仕様（固定電話は10桁、携帯は11桁）に合わせて、
10〜11桁の数字のみ を受け付ける仕様にしました。

もし「5桁まで」の指示が「入力欄ごと（分割入力でそれぞれ最大5桁）」を意味する場合は、
digits_between ではなく max:5 を各入力欄に適用すれば対応可能です。
