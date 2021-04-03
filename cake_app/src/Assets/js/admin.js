import jQuery from 'jquery';
window.$ = jQuery;
window.jQuery = jQuery;

import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/plugins/chart.js/Chart.min.js';

import 'select2/dist/js/select2.min.js';

window.moment = require('moment');
window.moment.locale('ja');
import 'tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js';

import 'bootstrap-fileinput/js/plugins/piexif.min.js';
window.Sortable = require('bootstrap-fileinput/js/plugins/sortable.min.js');
import 'bootstrap-fileinput/js/fileinput.min.js';
import 'bootstrap-fileinput/themes/explorer-fas/theme.min.js';
import 'bootstrap-fileinput/js/locales/ja.js';

import 'summernote/dist/summernote-bs4.min.js';
import 'summernote/dist/lang/summernote-ja-JP.min.js';

$(() => {
    // tempusdominus-bootstrap-4のフォーカスが外れたとき、自動で非表示となるようにする
    $(document).on('mouseup touchend', e => {
        var container = $('.bootstrap-datetimepicker-widget');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.prev().datetimepicker('hide');
        }
    });
});