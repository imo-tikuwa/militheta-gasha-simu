<?php
return [
    'function_title' => '復刻情報',
    'columns' => [
        'id' => [
            'search' => true,
            'listview' => true,
            'require' => true,
            'label' => 'ID',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
        ],
        'gasha_id' => [
            'search' => true,
            'listview' => true,
            'require' => true,
            'label' => 'ガシャID',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
            'input_type' => 'foreign_key',
            'search_type' => '=',
            'foreign_table' => 'gashas',
            'foreign_disp_column' => 'title',
        ],
        'card_id' => [
            'search' => true,
            'listview' => true,
            'require' => true,
            'label' => 'カードID',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
            'input_type' => 'foreign_key',
            'search_type' => '=',
            'foreign_table' => 'cards',
            'foreign_disp_column' => 'name',
        ],
        'created' => [
            'search' => false,
            'listview' => false,
            'require' => true,
            'label' => '作成日時',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
        ],
        'modified' => [
            'search' => false,
            'listview' => true,
            'require' => true,
            'label' => '更新日時',
            'col_lg_size' => 6,
            'col_md_size' => 6,
            'col_sm_size' => 12,
        ],
    ],
    'options' => [
        'bake_crud' => true,
        'one_record_limited' => false,
        'is_search_form' => true,
        'use_freeword_search' => true,
        'yubinbango' => false,
        'paging_limit' => 20,
        'left_side_menu_icon_class' => 'fas fa-table',
        'delete_function' => true,
        'delete_type' => 'logical',
        'init_order_column' => 'id',
        'init_order_direction' => 'asc',
        'text_size' => 'small',
        'input_size' => 'small',
        'button_size' => 'small',
        'table_size' => 'small',
        'csv_export' => true,
        'csv_import' => false,
        'csv_encoding' => 'utf8',
        'excel_export' => true,
        'excel_import' => false,
        'bake_swagger_api' => false,
    ],
];
