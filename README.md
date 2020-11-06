# militheta-gasha-simu
アイドルマスター ミリオンライブ！シアターデイズのガシャシミュレータです。  
ミリシタのサービス開始からこれまでに実装されたカードのデータを元にガシャのシミュレートを行うことが可能です。

## デモサイト
https://milligasha.imo-tikuwa.com/

## Dockerを使った環境構築
https://github.com/imo-tikuwa/docker-militheta-gasha-simu

## Dockerを使わない環境構築
非公開の開発用プラグインを使用しているのでcomposer installの際--no-devを付ける必要あり  
また、Application.php内に記載のCake3AdminBakerプラグインの読み込みを削除する必要あり
```
git clone https://github.com/imo-tikuwa/militheta-gasha-simu.git
cd militheta-gasha-simu/cake_app
composer install --no-dev
bin\cake plugin unload Cake3AdminBaker
npm install
npm run build

mysql < ../env/create_millitheta.sql
mysql millitheta < ../env/millitheta.sql
```

## .envについて
```
#!/usr/bin/env bash

# CakePHPの標準の設定
export APP_NAME="MillithetaGashaSimu"
export DEBUG="false"
export APP_ENCODING="UTF-8"
export APP_DEFAULT_LOCALE="ja_JP"
export APP_DEFAULT_TIMEZONE="Asia/Tokyo"
export SECURITY_SALT="jHRuXqo8amappyTo5GjUFws6iPCX4hGyVZ5zsjdPHWOk3WY9gTjgOwpzZoJYoRES"

# CakePHPのデータベース設定
export DATABASE_HOST="localhost"
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
