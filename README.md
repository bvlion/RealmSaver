# ブックマークアプリのRealmデータバックアップ用サーバー

PHPで作成  
PHPのFWはSlimを採用  
PDOでDBにアクセスする  
module.phpを規定にしている

## 使い方

開発ではdocker-composeを用いてローカルで実行する  
事前にcomposer installを実施しておくこと  
.envのslack.urlは自身の環境に合わせて設定すること

```
docker-compose up -d
```

## module.php
動作関連を設定

### URL
* 404
* 500
* /{hash}/restore GET（データ取得）
* /{hash}/save POST（データ保存）
* /inquiry POST（Slackへメッセージ送信）

## lib
* db_access.php（DBアクセス情報を設定および処理）
* slack.php（Slackへメッセージを送信する処理）
* Log.php（error_logを使ったログ出力）