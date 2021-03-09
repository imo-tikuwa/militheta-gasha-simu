$(() => {

    // フリーワード検索
    $('#card_reprints-freeword-search-snippet').on('keypress', e => {
        if (e.keyCode == 13) {
            $('#card_reprints-freeword-search-btn').trigger('click');
        }
    });

    // ガシャID
    $('#gasha_id').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

    // カードID
    $('#card_id').select2({
        theme: "bootstrap4",
        width: 'auto',
        dropdownAutoWidth: true,
    });

});
