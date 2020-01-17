<?php
return [
		'function_title' => 'カード',
		'columns' => [
				'id' => [
						'search' => true,
						'listview' => true,
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
				'character_id' => [
						'search' => false,
						'listview' => true,
						'label' => 'キャラクター',
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'foreign_key',
						'foreign_table' => 'characters',
						'foreign_disp_column' => 'name',
						'boolean_init_value' => 'null',
				],
				'name' => [
						'search' => true,
						'listview' => true,
						'label' => 'カード名',
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
				'rarity' => [
						'search' => true,
						'listview' => true,
						'label' => 'レアリティ',
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'select',
						'boolean_init_value' => 'null',
						'selections' => [
								'01' => 'N',
								'02' => 'R',
								'03' => 'SR',
								'04' => 'SSR',
						],
						'default_value' => '02',
				],
				'type' => [
						'search' => true,
						'listview' => true,
						'label' => 'タイプ',
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'select',
						'selections' => [
								'01' => 'Princess',
								'02' => 'Fairy',
								'03' => 'Angel',
						],
						'default_value' => '01',
				],
				'add_date' => [
						'search' => true,
						'listview' => true,
						'label' => '実装日',
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'date',
				],
				'gasha_include' => [
						'search' => true,
						'listview' => true,
						'label' => 'ガシャ対象？',
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'boolean',
						'boolean_init_value' => '1',
				],
				'limited' => [
						'search' => true,
						'listview' => true,
						'label' => '限定？',
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'boolean',
						'boolean_init_value' => '0',
				],
				'created' => [
						'search' => false,
						'listview' => false,
						'label' => '作成日時',
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
				'modified' => [
						'search' => false,
						'listview' => true,
						'label' => '更新日時',
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
		],
		'options' => [
				'is_search_form' => true,
				'yubinbango' => false,
				'paging_limit' => 20,
				'left_side_menu_icon_class' => 'fas fa-table',
				'google_map_api_key' => '',
				'delete_function' => false,
				'csv_export' => true,
				'csv_import' => true,
		],
];
