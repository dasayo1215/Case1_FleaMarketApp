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
    - メール開発環境：MailHog
    - 決済サービス：Stripe

## ER図


## URL
    - 開発環境：http://localhost/
    - phpMyAdmin：http://localhost:8080/
    - MailHog UI: http://localhost:8025/
        ※ローカル環境で送信された認証メールや通知メールを確認できます。
    - Stripe Dashboard：https://dashboard.stripe.com/test
        ※テストモードでの支払い状況やWebhookイベントの確認に使います。

## Stripeの設定について
    1. [Stripe](https://dashboard.stripe.com/register)でアカウントを作成してください（テストモードでOK）。
    2. ダッシュボードからAPIキー（公開可能キーと秘密キー）を取得してください。
    3. プロジェクトの `.env` ファイルに以下の環境変数を追加します。

        ```env
        STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxx
        STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxx
        ```

    4. `.env` はGit管理から除外しています。APIキーは絶対に公開しないよう注意してください。
    5. Stripeのテストカード番号は `4242 4242 4242 4242`（任意の有効期限・CVCで利用可能）です。

## MailHogの利用について
    - メール送信機能の動作確認はMailHogのWeb UI（http://localhost:8025/）で行います。
    - Laravelの `.env` にメール送信設定は以下のようにしてあります。

        ```env
        MAIL_MAILER=smtp
        MAIL_HOST=mailhog
        MAIL_PORT=1025
        MAIL_USERNAME=null
        MAIL_PASSWORD=null
        MAIL_ENCRYPTION=null
        ```

    - これにより、ローカルのメール送信はMailHog経由となり、実際に外部には送信されません。
    - メール認証や通知メールを受け取ったかどうかは、MailHogのUIで確認できます。

## その他
    - ダミーの商品データにはカテゴリーを追加した

## 画面例
    - 登録ページ

