@echo off

cd /D %~dp0
cd ..\cake_app\bin

REM ���[�J���G���A�ڑ��̃l�b�g���[�NIP���擾
for /f "tokens=1,2* usebackq delims=^:" %%i in (`netsh interface ipv4 show address "���[�J�� �G���A�ڑ�" ^| findstr "IP �A�h���X" ^| findstr /n /r "."`) do @set NetworkIP=%%k

REM Wi-Fi�ڑ��̃l�b�g���[�NIP���擾
if "%NetworkIP%" == "" (
    for /f "tokens=1,2* usebackq delims=^:" %%i in (`netsh interface ipv4 show address "Wi-Fi" ^| findstr "IP �A�h���X" ^| findstr /n /r "."`) do @set NetworkIP=%%k
)
call :Trim %NetworkIP%

echo %NetworkIP% | findstr /B 192.168. > nul
if %ERRORLEVEL% equ 0 (
    start cake server -H %NetworkIP% -p 80
    start cake server -H %NetworkIP% -p 8080
)


REM �擾����IP�̋󔒍폜
:Trim
set NetworkIP=%*