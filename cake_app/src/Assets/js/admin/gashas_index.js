$(function(){

    // CSVインポート
    $('#csv-import-file').on('change', function(){
        $('#csv-import-form').submit();
    });

    // フリーワード検索
    $('#gashas-freeword-search-snippet').on('keypress', function(e) {
        if (e.keyCode == 13) {
            $('#gashas-freeword-search-btn').trigger('click');
        }
    });
    $('#gashas-freeword-search-btn').on('click', function(){
        let freeword_snippet = $('#gashas-freeword-search-snippet').val(),
        freeword_snippet_format = $('.gashas-freeword-search-snippet-format:checked').val();
        $('#gashas-freeword-hidden-search-snippet').val(freeword_snippet);
        $('#gashas-freeword-hidden-search-snippet-format').val(freeword_snippet_format);
        $('#gashas-freeword-search-form').submit();
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

});
