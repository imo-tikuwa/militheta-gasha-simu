<?php
return [
    'SystemProperties' => [
        'RoleList' => [
            0 => ROLE_READ,
            1 => ROLE_WRITE,
            2 => ROLE_DELETE,
            3 => ROLE_CSV_EXPORT,
            4 => ROLE_CSV_IMPORT,
        ],
        'RoleBadgeClass' => [
            ROLE_READ => 'badge badge-primary',
            ROLE_WRITE => 'badge badge-danger',
            ROLE_DELETE => 'badge badge-warning text-white',
            ROLE_CSV_EXPORT => 'badge badge-info',
            ROLE_CSV_IMPORT => 'badge badge-success',
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
            'one_record_limited' => false,
        ],
        'Characters' => [
            'controller' => 'Characters',
            'label' => 'キャラクター',
            'icon_class' => 'fas fa-female',
            'one_record_limited' => false,
        ],
        'Cards' => [
            'controller' => 'Cards',
            'label' => 'カード',
            'icon_class' => 'far fa-id-card',
            'one_record_limited' => false,
        ],
        'CardReprints' => [
            'controller' => 'CardReprints',
            'label' => '復刻情報',
            'icon_class' => 'fas fa-table',
            'one_record_limited' => false,
        ],
        'GashaPickups' => [
            'controller' => 'GashaPickups',
            'label' => 'ピックアップ情報',
            'icon_class' => 'fas fa-level-up-alt',
            'one_record_limited' => false,
        ],
    ],
    'ThumbnailOptions' => [
    ],
    'ExcelOptions' => [
        'CardReprints' => [
            'version' => '8934a00438cc7b41b85f418f348904549f48cce2f9c2bda85676d982319a2875a36c3740bc79c1a7aa712f21e667ff8c91b4e101fbad64aff051a835fd2bcb48',
        ],
        'Cards' => [
            'version' => '0c3a4ef8a0555dccd9f6fb30bfe867a7f7c63f1cf3430894d17710ca486c42a5c14ec348f14253fbe9146e597b2e0bc6f15c4af3e5bc1a55c17693609b2fe0fd',
        ],
        'Characters' => [
            'version' => 'b25b294cb4deb69ea00a4c3cf3113904801b6015e5956bd019a8570b1fe1d6040e944ef3cdee16d0a46503ca6e659a25f21cf9ceddc13f352a3c98138c15d6af',
        ],
        'GashaPickups' => [
            'version' => '8934a00438cc7b41b85f418f348904549f48cce2f9c2bda85676d982319a2875a36c3740bc79c1a7aa712f21e667ff8c91b4e101fbad64aff051a835fd2bcb48',
        ],
        'Gashas' => [
            'version' => 'b25b294cb4deb69ea00a4c3cf3113904801b6015e5956bd019a8570b1fe1d6040e944ef3cdee16d0a46503ca6e659a25f21cf9ceddc13f352a3c98138c15d6af',
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
        ],
        'GashaPickups' => [
            ROLE_READ => 'ピックアップ情報読込',
            ROLE_WRITE => 'ピックアップ情報書込',
            ROLE_DELETE => 'ピックアップ情報削除',
            ROLE_CSV_EXPORT => 'ピックアップ情報CSVエクスポート',
        ],
        'CardReprints' => [
            ROLE_READ => '復刻情報読込',
            ROLE_WRITE => '復刻情報書込',
            ROLE_DELETE => '復刻情報削除',
            ROLE_CSV_EXPORT => '復刻情報CSVエクスポート',
        ],
        'Cards' => [
            ROLE_READ => 'カード読込',
            ROLE_WRITE => 'カード書込',
            ROLE_DELETE => 'カード削除',
            ROLE_CSV_EXPORT => 'カードCSVエクスポート',
            ROLE_CSV_IMPORT => 'カードCSVインポート',
        ],
        'Gashas' => [
            ROLE_READ => 'ガシャ読込',
            ROLE_WRITE => 'ガシャ書込',
            ROLE_DELETE => 'ガシャ削除',
            ROLE_CSV_EXPORT => 'ガシャCSVエクスポート',
            ROLE_CSV_IMPORT => 'ガシャCSVインポート',
        ],
    ],
    'Others' => [
        'search_snippet_format' => [
            'AND' => ' AND',
            'OR' => ' OR',
        ],
    ],
];
