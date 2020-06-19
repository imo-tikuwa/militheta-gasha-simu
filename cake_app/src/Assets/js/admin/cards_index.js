$(function(){

    // CSVインポート
    $('#csv-import-file').on('change', function(){
        $('#csv-import-form').submit();
    });
    // キャラクター
    $('#character_id').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

    // レアリティ
    $('#rarity').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

    // タイプ
    $('#type').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

    // 実装日
    $('#add_date-datepicker').datetimepicker({
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

    // ガシャ対象？
    $('#gasha_include').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

    // 限定？
    $('#limited').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

});
