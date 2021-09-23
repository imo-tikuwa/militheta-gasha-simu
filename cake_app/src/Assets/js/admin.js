import jQuery from 'jquery';
window.$ = jQuery;
window.jQuery = jQuery;

import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/plugins/chart.js/Chart.min.js';

import 'select2/dist/js/select2.min.js';

import 'moment/locale/ja.js';
window.moment = require('moment');
window.moment.locale('ja');
import 'tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js';

$(() => {
    // tempusdominus-bootstrap-4のフォーカスが外れたとき、自動で非表示となるようにする
    $(document).on('mouseup touchend', e => {
        var container = $('.bootstrap-datetimepicker-widget');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.prev().datetimepicker('hide');
        }
    });
});