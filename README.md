README
# アプリケーション名
    お問い合わせフォーム

## 環境構築
    - Dockerビルド
        1. git clone リンク：git@github.com:dasayo1215/Case1_FleaMarketApp.git
        2. DockerDesktopアプリを立ち上げる
        3. docker-compose up -d --build
    *MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

    - Laravel環境構築
        1. docker-compose exec php bash
        2. composer install
        3. .env.exampleファイルの名前を変更して.envファイルを作成。
        4. .envに以下の環境変数を追加
              DB_CONNECTION=mysql
              DB_HOST=mysql
              DB_PORT=3306
              DB_DATABASE=laravel_db
              DB_USERNAME=laravel_user
              DB_PASSWORD=laravel_pass
        5. php artisan key:generate
        6. php artisan migrate
        7. php artisan db:seed

## 使用技術(実行環境)
    - OS：Windows 11
    - フレームワーク：Laravel 8.x
    - プログラミング言語：PHP 8.x
    - コンテナ管理：Docker
    - データベース： MySQL 8.0.x
    - バージョン管理：Git / GitHub

## ER図


## URL
    - 開発環境：http://localhost/
    - phpMyAdmin：http://localhost:8080/

## その他
    - ダミーの商品データにはカテゴリーを追加した

## 画面例
    - 登録ページ

