<?php
return [
    'function_title' => 'キャラクター',
    'columns' => [
        'id' => [
            'search' => true,
            'listview' => true,
            'require' => false,
            'label' => 'ID',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
        ],
        'name' => [
            'search' => true,
            'listview' => true,
            'require' => true,
            'label' => '名前',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 6,
            'input_type' => 'text',
            'search_type' => '=',
        ],
        'created' => [
            'search' => false,
            'listview' => false,
            'require' => false,
            'label' => '作成日時',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
        ],
        'modified' => [
            'search' => false,
            'listview' => true,
            'require' => false,
            'label' => '更新日時',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
        ],
    ],
    'options' => [
        'one_record_limited' => false,
        'is_search_form' => true,
        'use_freeword_search' => false,
        'yubinbango' => false,
        'paging_limit' => 20,
        'left_side_menu_icon_class' => 'fas fa-female',
        'delete_function' => false,
        'init_order_column' => 'id',
        'init_order_direction' => 'asc',
        'text_size' => 'small',
        'input_size' => 'small',
        'button_size' => 'small',
        'table_size' => 'small',
        'csv_export' => true,
        'csv_import' => false,
        'csv_encoding' => 'utf8',
    ],
];
