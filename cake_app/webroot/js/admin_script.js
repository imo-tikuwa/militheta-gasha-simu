$(function(){
	// プルダウンのリプレイス
	$("select").select2({
		theme: "bootstrap4",
		width: 'auto',
		dropdownAutoWidth: true,
	});

	// サイドメニューの開閉を記憶する
	$("#sidemenu-toggle").on('click', function (){
		var changed_sidemenu_css_class = $("body").hasClass('sidebar-open') ? "sidebar-collapse" : "sidebar-open";
		Cookies.set('sidemenu-toggle-class', changed_sidemenu_css_class);
	});

	// tempusdominus-bootstrap-4のフォーカスが外れたとき、自動で非表示となるようにする
	$(document).on('mouseup touchend', function (e) {
		var container = $('.bootstrap-datetimepicker-widget');
		if (!container.is(e.target) && container.has(e.target).length === 0) {
		  container.prev().datetimepicker('hide');
		}
	});
});