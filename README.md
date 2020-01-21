# militheta-gasha-simu
ソシャゲの開発に携わったことがない人が思いつきで実装したガシャ（ガチャ）シミュレータです

## install
```
git clone https://github.com/imo-tikuwa/militheta-gasha-simu.git
cd militheta-gasha-simu/cake_app
composer install
cd webroot
npm ci

cd ../..
mysql < env/create_millitheta.sql
mysql millitheta < env/millitheta.sql
```

## usage
```
env/start.bat
```
browser access http://192.168.1.xxx/admin/auth/login  
user: admin@imo-tikuwa.com    
pass: password  

ミリシタのサービス開始から2020年1月までに実装されたカードのデータを元にガシャのシミュレートを行うことが可能です。
