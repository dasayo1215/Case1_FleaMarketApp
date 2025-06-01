README
# アプリケーション名
    お問い合わせフォーム

## 使用技術(実行環境)
    - OS：Windows 11
    - フレームワーク：Laravel 8.x
    - プログラミング言語：PHP 8.x
    - コンテナ管理：Docker
    - データベース： MySQL 8.0.x
    - バージョン管理：Git / GitHub
    - メール開発環境：MailHog
    - 決済サービス：Stripe
    ※一部JavaScriptを使ったのは書いた方がいいのか？？

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

# MailHogの利用について
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

# Stripeの設定について
    1. [Stripe](https://dashboard.stripe.com/register)でアカウントを作成してください（テストモードでOK）。
    2. ダッシュボードからAPIキー（公開可能キーと秘密キー）を取得してください。
    3. プロジェクトの `.env` ファイルに以下の環境変数を追加します。

        ```env
        STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxx
        STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxx
        ```

    4. `.env` はGit管理から除外しています。APIキーは絶対に公開しないよう注意してください。


# ngrokとwebhookの設定について（毎回設定）
    支払いテストの実行のため、ngrokを利用します。
    ※あくまで開発専用で、本番環境では使わないようにしてください。
    1. ngrok http 80
    2. 出力されるURLをコピー
        > Forwarding  https://3092-xx-xx-xx.ngrok-free.app -> http://localhost:80
        上記では ”https://3092-xx-xx-xx.ngrok-free.app” の部分をコピーします。
    3. Stripeダッシュボードの送信先にWebhook URLを登録
        上記でコピーしたURLの後ろに「/webhook/stripe」を追記して登録してください。
        例）https://xxxx.ngrok-free.app/webhook/stripe
    ※利用終了後はCtrl+Cなどでトンネルを停止してください。

# Stripe CLIの設定について（毎回設定）
    1. なんか設定する
        自分の場合はDocker利用で動かす。下記でキーが出てくる。
        > Ready! You are using Stripe API Version [2025-04-30.basil]. Your webhook signing secret is whsec_xxxxxxxxxxxxxxxxxxxxx(^C to quit)
    2. プロジェクトの `.env` ファイルに以下の環境変数を追加します。

        ```env
        STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxx
        ```

# 支払いテストについて
    1. Stripeのテストカード番号は `4242 4242 4242 4242`（任意の有効期限・CVCで利用可能）です。
    2. 失敗用カードは、、
    3. コンビニ支払いのテストには。テスト用電話番号 `11111111110` を利用できます。3分後に決済が完了したとみなされます。

## URL
    - 開発環境：http://localhost/
    - phpMyAdmin：http://localhost:8080/
    - MailHog UI: http://localhost:8025/
        ※ローカル環境で送信された認証メールや通知メールを確認できます。
    - Stripe Dashboard：https://dashboard.stripe.com/test
        ※テストモードでの支払い状況やWebhookイベントの確認に使います。

## その他
    - ダミーの商品データにはカテゴリーを追加した
    - メールは初回登録時のみ自動送信、その他認証に引っかかったときは自動送信されず認証完了を促すページにリダイレクト。
    -「認証はこちらから」ボタンでmailhogの画面に遷移。

## ER図


## 画面例
    - 登録ページ

