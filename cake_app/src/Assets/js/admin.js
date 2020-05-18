//import 'jquery/dist/jquery.min.js';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/plugins/chart.js/Chart.min.js';

import 'select2/dist/js/select2.min.js';

window.moment = require('moment');
window.moment.locale('ja');
import 'tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js';

import 'bootstrap-fileinput/js/plugins/piexif.min.js';
import 'bootstrap-fileinput/js/plugins/purify.min.js';
import 'bootstrap-fileinput/js/plugins/sortable.min.js';
import 'bootstrap-fileinput/js/fileinput.min.js';
import 'bootstrap-fileinput/themes/explorer-fas/theme.min.js';
import 'bootstrap-fileinput/js/locales/ja.js';

import 'summernote/dist/summernote-bs4.min.js';
import 'summernote/dist/lang/summernote-ja-JP.min.js';

import 'bootstrap4-tagsinput-douglasanpa/tagsinput.js';

var Cookies = require('js-cookie');

$(function(){

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