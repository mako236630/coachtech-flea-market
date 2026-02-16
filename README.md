
# coachtech フリマ

## 環境構築

**Dockerビルド**

- git clone git@github.com:mako236630/coachtech-flea-market.git
- cd coachtech-flea-market 
- docker-compose up -d --build

**laravel 環境構築**

- docker-compose exec php bash
- composer install
- cp .env.example .env  
.envは、docker-compose.ymlの定義に合わせて修正してください。
- php artisan key:generate
- php artisan migrate
- php artisan db:seed

## 開発環境

- 商品一覧画面: http://localhost/
- ログイン画面: http://localhost/login  
アプリの機能確認には、以下のテストアカウントをご利用ください。  
メールアドレス: test2@example.com  
パスワード: password789

- 会員登録画面: http://localhost/register
- phpMyAdmin: http://localhost:8080/

## 使用技術
- nginx 1.21.1
- php 8.1.34
- laravel 8.83.8
-MySQL 8.0.26

## ER図  
配送先住所: ユーザーがプロフィール住所を変更しても過去の購入履歴に影響が出ないよう、購入確定時に住所情報をitemsテーブルへ直接保存する設計にしました。  
決済方法: Stripeを使用している為、外部API連携を考慮しitemsテーブルのpayment_methodに文字列による保存方法にしました。  
![ER図](er-diagram.drawio.png)