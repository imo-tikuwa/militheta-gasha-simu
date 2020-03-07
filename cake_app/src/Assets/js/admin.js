//import '@fortawesome/fontawesome-free/css/all.css';

//import 'jquery/dist/jquery.min.js';

//import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

//import 'admin-lte/dist/css/adminlte.min.css';
import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/plugins/chart.js/Chart.min.js';

//import 'select2/dist/css/select2.min.css';
import 'select2/dist/js/select2.min.js';
//import '@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css';

window.moment = require('moment');
window.moment.locale('ja');
//import 'tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css';
import 'tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js';

//import '//fonts.googleapis.com/icon?family=Material+Icons';
//import 'bootstrap-fileinput/css/fileinput.min.css';
//import 'bootstrap-fileinput/themes/explorer-fas/theme.min.css';
import 'bootstrap-fileinput/js/plugins/piexif.min.js';
import 'bootstrap-fileinput/js/plugins/purify.min.js';
import 'bootstrap-fileinput/js/plugins/sortable.min.js';
import 'bootstrap-fileinput/js/fileinput.min.js';
import 'bootstrap-fileinput/themes/explorer-fas/theme.min.js';
import 'bootstrap-fileinput/js/locales/ja.js';

//import 'summernote/dist/summernote-bs4.css';
import 'summernote/dist/summernote-bs4.min.js';
import 'summernote/dist/lang/summernote-ja-JP.min.js';

//import 'bootstrap4-tagsinput-douglasanpa/tagsinput.css';
import 'bootstrap4-tagsinput-douglasanpa/tagsinput.js';

//import 'js-cookie/src/js.cookie.js';
var Cookies = require('js-cookie');

//import 'fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css';
import 'fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js';

//import '../css/admin_style.css';

$(function(){
	// プルダウンのリプレイス
	$(".select2").select2({
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