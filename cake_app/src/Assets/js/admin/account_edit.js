$(function(){

  // パスワードのトグル表示
  $("#password-toggle").change(function(){
    if ($(this).prop('checked')) {
        $("#password").attr('type', 'text');
    } else {
        $("#password").attr('type', 'password');
    }
  });

});
