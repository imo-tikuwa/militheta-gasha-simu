<?php
return [
		'function_title' => 'カード',
		'columns' => [
				'id' => [
						'search' => true,
						'listview' => true,
						'col_lg_size' => 6,
						'col_md_size' => 6,
						'col_sm_size' => 12,
				],
				'character_id' => [
						'search' => true,
						'listview' => true,
						'label' => 'キャラクター',
						'col_lg_size' => 3,
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'foreign_key',
						'search_type' => '=',
						'foreign_table' => 'characters',
						'foreign_disp_column' => 'name',
				],
				'name' => [
						'search' => true,
						'listview' => true,
						'label' => 'カード名',
						'col_lg_size' => 6,
						'col_md_size' => 6,
						'col_sm_size' => 12,
						'input_type' => 'text',
						'search_type' => 'LIKE',
				],
				'rarity' => [
						'search' => true,
						'listview' => true,
						'label' => 'レアリティ',
						'col_lg_size' => 3,
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'select',
						'search_type' => '=',
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
						'col_lg_size' => 3,
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'select',
						'search_type' => '=',
						'selections' => [
								'01' => 'Princess',
								'02' => 'Fairy',
								'03' => 'Angel',
								'04' => 'Ex',
						],
				],
				'add_date' => [
						'search' => true,
						'listview' => true,
						'label' => '実装日',
						'col_lg_size' => 3,
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'date',
						'search_type' => '=',
				],
				'gasha_include' => [
						'search' => true,
						'listview' => true,
						'label' => 'ガシャ対象？',
						'col_lg_size' => 3,
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'boolean',
						'search_type' => '=',
						'boolean_init_value' => '1',
				],
				'limited' => [
						'search' => true,
						'listview' => true,
						'label' => '限定？',
						'col_lg_size' => 3,
						'col_md_size' => 3,
						'col_sm_size' => 12,
						'input_type' => 'boolean',
						'search_type' => '=',
						'boolean_init_value' => '0',
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
				'one_record_limited' => false,
				'is_search_form' => true,
				'yubinbango' => false,
				'paging_limit' => 20,
				'left_side_menu_icon_class' => 'far fa-id-card',
				'delete_function' => false,
				'init_order_column' => 'id',
				'init_order_direction' => 'desc',
				'csv_export' => true,
				'csv_import' => true,
		],
];
