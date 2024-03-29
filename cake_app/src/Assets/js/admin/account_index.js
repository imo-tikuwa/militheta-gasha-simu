$(function(){

    // 二段階認証用のQRコードがあったらモーダルを表示
    if ($(document).find('#qr-modal')[0]) {
        $('#qr-modal').modal('show');
    }

    // 発行済みのQRコードの再表示
    $('.redraw-qr').on('click', function(){
        $('#redraw-qr-target-id').text('管理者ID：' + $(this).data('account-id') + 'の認証コードを表示しています。');
        $('#redraw-qr-img').attr('src', $(this).data('qr-url'));
        $('#redraw-qr-modal').modal('show');
    });
});
