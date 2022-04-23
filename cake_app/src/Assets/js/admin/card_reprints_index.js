$(() => {

    // フリーワード検索
    $('#card_reprints-freeword-search-snippet').on('keypress', e => {
        if (e.keyCode == 13) {
            $('#card_reprints-freeword-search-btn').trigger('click');
        }
    });

    // ガシャID
    $('#gasha-id').select2({
        theme: "bootstrap-5",
        width: 'auto',
        dropdownAutoWidth: true,
    });

    // カードID
    $('#card-id').select2({
        theme: "bootstrap-5",
        width: 'auto',
        dropdownAutoWidth: true,
    });

});
