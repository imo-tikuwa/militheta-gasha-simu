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

## 使い方
ビルトインサーバーを起動するWindows用のバッチがあります。  
コマンドプロンプトから実行するかダブルクリックするかVSCodeのターミナルから呼び出してください。  
Wifi接続だとIPが取得できないかも  
```
env/start.bat
```
http://192.168.1.xxx/admin/auth/login  
user: admin@imo-tikuwa.com    
pass: password  
