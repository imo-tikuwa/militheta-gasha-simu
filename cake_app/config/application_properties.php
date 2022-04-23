<?php
return [
    'SystemProperties' => [
        'RoleList' => [
            0 => ROLE_READ,
            1 => ROLE_WRITE,
            2 => ROLE_DELETE,
            3 => ROLE_CSV_EXPORT,
            4 => ROLE_CSV_IMPORT,
            5 => ROLE_EXCEL_EXPORT,
            6 => ROLE_EXCEL_IMPORT,
        ],
        'RoleBadgeClass' => [
            ROLE_READ => 'badge bg-primary',
            ROLE_WRITE => 'badge bg-danger',
            ROLE_DELETE => 'badge bg-warning text-white',
            ROLE_CSV_EXPORT => 'badge bg-info',
            ROLE_CSV_IMPORT => 'badge bg-success',
            ROLE_EXCEL_EXPORT => 'badge bg-info',
            ROLE_EXCEL_IMPORT => 'badge bg-success',
        ],
    ],
    'BakedFunctions' => [
        'CardReprints' => '復刻情報',
        'Cards' => 'カード',
        'Characters' => 'キャラクター',
        'GashaPickups' => 'ピックアップ情報',
        'Gashas' => 'ガシャ',
    ],
    'Codes' => [
        'Cards' => [
            'rarity' => [
                '01' => 'N',
                '02' => 'R',
                '03' => 'SR',
                '04' => 'SSR',
            ],
            'type' => [
                '01' => 'Princess',
                '02' => 'Fairy',
                '03' => 'Angel',
                '04' => 'Ex',
            ],
            'gasha_include' => [
                1 => 'true',
                0 => 'false',
            ],
            'limited' => [
                '01' => '恒常',
                '02' => '限定',
                '03' => 'フェス限定',
                '04' => 'SHS限定',
            ],
        ],
    ],
    'HeaderConfig' => [
        1 => [
            'title' => 'Yahoo! JAPAN',
            'link' => 'https://yahoo.co.jp/',
        ],
        2 => [
            'title' => 'Google',
            'link' => 'https://www.google.com/',
        ],
        3 => [
            'title' => 'ミリシタ　攻略まとめWiki',
            'link' => 'https://imasml-theater-wiki.gamerch.com/',
        ],
    ],
    'FooterConfig' => [
        'buttons' => [
            1 => [
                'button_text' => 'BLOG',
                'button_icon' => 'fas fa-user',
                'button_link' => 'https://blog.imo-tikuwa.com/',
            ],
            2 => [
                'button_text' => 'GitHub',
                'button_icon' => 'fab fa-github',
                'button_link' => 'https://github.com/imo-tikuwa/',
            ],
        ],
        'copylight' => [
            'from' => '2020',
            'text' => 'militheta-gasha-simu',
            'link' => 'https://github.com/imo-tikuwa/militheta-gasha-simu',
        ],
    ],
    'LeftSideMenu' => [
        'Gashas' => [
            'controller' => 'Gashas',
            'label' => 'ガシャ',
            'icon_class' => 'fas fa-database',
        ],
        'Characters' => [
            'controller' => 'Characters',
            'label' => 'キャラクター',
            'icon_class' => 'fas fa-female',
        ],
        'Cards' => [
            'controller' => 'Cards',
            'label' => 'カード',
            'icon_class' => 'far fa-id-card',
        ],
        'CardReprints' => [
            'controller' => 'CardReprints',
            'label' => '復刻情報',
            'icon_class' => 'fas fa-table',
        ],
        'GashaPickups' => [
            'controller' => 'GashaPickups',
            'label' => 'ピックアップ情報',
            'icon_class' => 'fas fa-level-up-alt',
        ],
    ],
    'FileUploadOptions' => [
    ],
    'ExcelOptions' => [
        'CardReprints' => [
            'version' => 'd0ccdd75726cc23169aac6d2d14d87e1a5dfc2a624aba346ef9c77079c9e4607b87f088e4ac3f6bbd039a21198776518e81b66ef0adf8f8359e3124389200e59',
        ],
        'Cards' => [
            'version' => '8d08ffc9661ce599ef98ea2ed3418bdec76b4b58a6c5e409e3493aaf175fc6b007f3d7edf25840d12ff516c3ccad367709966222707df70eb0caece56cbe999c',
        ],
        'Characters' => [
            'version' => '73842c4534be22815ce74e9bf7c0dfd147f4c39b83ef1137c2c9a80066383bbc8093679ad2923d6e653c38581e34540ca7a9b0e6dabdd79f674a7225a5bc2fd3',
        ],
        'GashaPickups' => [
            'version' => '82948b6d6a0c2474be1e4b0ea0c94b5a77e0cabf51acb9918b4d4e0fd3547630e8b7f119701bb6094cd409af1509893ba25f5b8f0bf7199798f490dfb8603fd7',
        ],
        'Gashas' => [
            'version' => '1383522d3e3ac395762bb2e0e86a4f60c115fae6c6bc028e57329add671c5c2d73e31b7609d899b3afc1e631afae3facba8d78f8a923fbc51736ab452fd64367',
        ],
    ],
    'InitialOrders' => [
        'CardReprints' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
        'Cards' => [
            'sort' => 'id',
            'direction' => 'desc',
        ],
        'Characters' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
        'GashaPickups' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
        'Gashas' => [
            'sort' => 'id',
            'direction' => 'desc',
        ],
    ],
    'AdminRoles' => [
        'Characters' => [
            ROLE_READ => 'キャラクター読込',
            ROLE_WRITE => 'キャラクター書込',
            ROLE_DELETE => 'キャラクター削除',
            ROLE_CSV_EXPORT => 'キャラクターCSVエクスポート',
            ROLE_EXCEL_EXPORT => 'キャラクターExcelエクスポート',
        ],
        'GashaPickups' => [
            ROLE_READ => 'ピックアップ情報読込',
            ROLE_WRITE => 'ピックアップ情報書込',
            ROLE_DELETE => 'ピックアップ情報削除',
            ROLE_CSV_EXPORT => 'ピックアップ情報CSVエクスポート',
            ROLE_EXCEL_EXPORT => 'ピックアップ情報Excelエクスポート',
        ],
        'CardReprints' => [
            ROLE_READ => '復刻情報読込',
            ROLE_WRITE => '復刻情報書込',
            ROLE_DELETE => '復刻情報削除',
            ROLE_CSV_EXPORT => '復刻情報CSVエクスポート',
            ROLE_EXCEL_EXPORT => '復刻情報Excelエクスポート',
        ],
        'Cards' => [
            ROLE_READ => 'カード読込',
            ROLE_WRITE => 'カード書込',
            ROLE_DELETE => 'カード削除',
            ROLE_CSV_EXPORT => 'カードCSVエクスポート',
            ROLE_CSV_IMPORT => 'カードCSVインポート',
            ROLE_EXCEL_EXPORT => 'カードExcelエクスポート',
        ],
        'Gashas' => [
            ROLE_READ => 'ガシャ読込',
            ROLE_WRITE => 'ガシャ書込',
            ROLE_DELETE => 'ガシャ削除',
            ROLE_CSV_EXPORT => 'ガシャCSVエクスポート',
            ROLE_CSV_IMPORT => 'ガシャCSVインポート',
            ROLE_EXCEL_EXPORT => 'ガシャExcelエクスポート',
        ],
    ],
    'Others' => [
        'search_snippet_format' => [
            'AND' => ' AND',
            'OR' => ' OR',
        ],
    ],
];
