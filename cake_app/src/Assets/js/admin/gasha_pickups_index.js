$(function(){

    // フリーワード検索
    $('#gasha_pickups-freeword-search-snippet').on('keypress', function(e) {
        if (e.keyCode == 13) {
            $('#gasha_pickups-freeword-search-btn').trigger('click');
        }
    });
    $('#gasha_pickups-freeword-search-btn').on('click', function(){
        let freeword_snippet = $('#gasha_pickups-freeword-search-snippet').val(),
        freeword_snippet_format = $('.gasha_pickups-freeword-search-snippet-format:checked').val();
        $('#gasha_pickups-freeword-hidden-search-snippet').val(freeword_snippet);
        $('#gasha_pickups-freeword-hidden-search-snippet-format').val(freeword_snippet_format);
        $('#gasha_pickups-freeword-search-form').submit();
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
