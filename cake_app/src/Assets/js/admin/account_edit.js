$(function(){

    // パスワードのトグル表示
    $("#password-toggle").change(function(){
        if ($(this).prop('checked')) {
            $("#password").attr('type', 'text');
        } else {
            $("#password").attr('type', 'password');
        }
    });

    // 二段階認証
    $("#use_otp").change(function(){
        if ($(this).is(':checked')) {
            if (!confirm("「二段階認証を使用する」を有効にした場合、保存処理の後にQRコードが発行されます。\nQRコードはお手持ちのモバイル端末のGoogle Authenticatorで読み取る必要があります。")) {
                $(this).prop('checked', false);
            }
        }
    });

});
