@echo off

cd /D %~dp0
cd ..\cake_app\bin

REM ローカルエリア接続のネットワークIPを取得
for /f "tokens=1,2* usebackq delims=^:" %%i in (`netsh interface ipv4 show address "ローカル エリア接続" ^| findstr "IP アドレス" ^| findstr /n /r "."`) do @set NetworkIP=%%k

REM Wi-Fi接続のネットワークIPを取得
if "%NetworkIP%" == "" (
    for /f "tokens=1,2* usebackq delims=^:" %%i in (`netsh interface ipv4 show address "Wi-Fi" ^| findstr "IP アドレス" ^| findstr /n /r "."`) do @set NetworkIP=%%k
)
call :Trim %NetworkIP%

echo %NetworkIP% | findstr /B 192.168. > nul
if %ERRORLEVEL% equ 0 (
    start cake server -H %NetworkIP% -p 80
    start cake server -H %NetworkIP% -p 8080
)


REM 取得したIPの空白削除
:Trim
set NetworkIP=%*