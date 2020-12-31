$(function(){

    // フリーワード検索
    $('#gasha_pickups-freeword-search-snippet').on('keypress', function(e) {
        if (e.keyCode == 13) {
            $('#gasha_pickups-freeword-search-btn').trigger('click');
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
