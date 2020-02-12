<?php
return [
		'function_title' => 'キャラクター',
		'columns' => [
				'id' => [
						'search' => true,
						'listview' => true,
						'col_lg_size' => 6,
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
				'name' => [
						'search' => true,
						'listview' => true,
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
						'label' => '作成日時',
						'col_lg_size' => 6,
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
				'modified' => [
						'search' => false,
						'listview' => true,
						'label' => '更新日時',
						'col_lg_size' => 6,
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
		],
		'options' => [
				'is_search_form' => true,
				'yubinbango' => false,
				'paging_limit' => 20,
				'left_side_menu_icon_class' => 'fas fa-female',
				'google_map_api_key' => '',
				'delete_function' => false,
				'csv_export' => true,
				'csv_import' => false,
		],
];
