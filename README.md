# militheta-gasha-simu
アイドルマスター ミリオンライブ！シアターデイズのガシャシミュレータです。  
ミリシタのサービス開始から2020年1月までに実装されたカードのデータを元にガシャのシミュレートを行うことが可能です。

## デモサイト
https://milligasha.imo-tikuwa.com/

## インストール
非公開の開発用プラグインを使用しているのでcomposer installの際--no-devを付ける必要あり
```
git clone https://github.com/imo-tikuwa/militheta-gasha-simu.git
cd militheta-gasha-simu/cake_app
composer install --no-dev
cd webroot
npm ci

cd ../..
mysql < env/create_millitheta.sql
mysql millitheta < env/millitheta.sql
```

config/bootstrap.phpを開いてCake3AdminBakerプラグインを無効化
```
// プラグインのロード
Plugin::load('Cake3AdminBaker', ['bootstrap' => true, 'routes' => true]);
↓
// Plugin::load('Cake3AdminBaker', ['bootstrap' => true, 'routes' => true]);
```

## .envについて
```
#!/usr/bin/env bash

# CakePHP3の標準の設定
export APP_NAME="MillithetaGashaSimu"
export DEBUG="false"
export APP_ENCODING="UTF-8"
export APP_DEFAULT_LOCALE="ja_JP"
export APP_DEFAULT_TIMEZONE="Asia/Tokyo"
export SECURITY_SALT="7Tosv70DGOM0SGEJcyS6MjMWOWtsPKawUHc5CgjyxbiiQulIiQZfYCEdbcEY2r1A"

# CakePHP3のデータベース設定
export DATABASE_NAME="millitheta"
export DATABASE_PORT="33306"
export DATABASE_USER="millitheta"
export DATABASE_PASS="92xUCSRgoBqZ0qyB"
```

## 使い方
ビルトインサーバーを起動するWindows用のバッチがあります。  
コマンドプロンプトから実行するかダブルクリックするかVSCodeのターミナルから呼び出してください。  
```
env/start.bat
```
http://192.168.1.xxx/admin/auth/login  
user: admin@imo-tikuwa.com    
pass: password  
