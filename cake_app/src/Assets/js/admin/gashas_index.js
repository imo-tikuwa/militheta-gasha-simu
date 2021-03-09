$(() => {

    // CSVインポート
    $('.csv-import-btn').on('click', () => {
        $('#csv-import-file').trigger('click');
    });
    $('#csv-import-file').on('change', () => {
        $('#csv-import-form').submit();
    });

    // フリーワード検索
    $('#gashas-freeword-search-snippet').on('keypress', e => {
        if (e.keyCode == 13) {
            $('#gashas-freeword-search-btn').trigger('click');
        }
    });

    // ガシャ開始日
    $('#start_date-datepicker').datetimepicker({
        dayViewHeaderFormat: 'YYYY年 M月',
        locale: 'ja',
        buttons: {
            showClear: true
        },
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-alt',
            clear: 'far fa-trash-alt',
            close: 'fas fa-times'
        },
        format: 'YYYY-MM-DD',
    });
    $('#start_date-datepicker').on('change.datetimepicker', e => {
        if (e.date === false) {
            $('#start_date-datepicker').datetimepicker('hide');
        }
    });

    // ガシャ終了日
    $('#end_date-datepicker').datetimepicker({
        dayViewHeaderFormat: 'YYYY年 M月',
        locale: 'ja',
        buttons: {
            showClear: true
        },
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-alt',
            clear: 'far fa-trash-alt',
            close: 'fas fa-times'
        },
        format: 'YYYY-MM-DD',
    });
    $('#end_date-datepicker').on('change.datetimepicker', e => {
        if (e.date === false) {
            $('#end_date-datepicker').datetimepicker('hide');
        }
    });

});
