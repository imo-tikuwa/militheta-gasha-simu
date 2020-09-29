$(function(){

    // フリーワード検索
    $('#card_reprints-freeword-search-snippet').on('keypress', function(e) {
        if (e.keyCode == 13) {
            $('#card_reprints-freeword-search-btn').trigger('click');
        }
    });
    $('#card_reprints-freeword-search-btn').on('click', function(){
        let freeword_snippet = $('#card_reprints-freeword-search-snippet').val(),
        freeword_snippet_format = $('.card_reprints-freeword-search-snippet-format:checked').val();
        $('#card_reprints-freeword-hidden-search-snippet').val(freeword_snippet);
        $('#card_reprints-freeword-hidden-search-snippet-format').val(freeword_snippet_format);
        $('#card_reprints-freeword-search-form').submit();
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
