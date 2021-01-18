<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CardsFixture
 */
class CardsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => false,
            'default' => null,
            'comment' => 'ID',
            'autoIncrement' => true,
            'precision' => null,
        ],
        'character_id' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => true,
            'default' => null,
            'comment' => 'キャラクター',
            'precision' => null,
            'autoIncrement' => null,
        ],
        'name' => [
            'type' => 'string',
            'length' => 255,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'カード名',
            'precision' => null,
        ],
        'rarity' => [
            'type' => 'char',
            'length' => 2,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'レアリティ',
            'precision' => null,
        ],
        'type' => [
            'type' => 'char',
            'length' => 2,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'タイプ',
            'precision' => null,
        ],
        'add_date' => [
            'type' => 'date',
            'length' => null,
            'null' => true,
            'default' => null,
            'comment' => '実装日',
            'precision' => null,
        ],
        'gasha_include' => [
            'type' => 'boolean',
            'length' => null,
            'null' => true,
            'default' => '1',
            'comment' => 'ガシャ対象？',
            'precision' => null,
        ],
        'limited' => [
            'type' => 'char',
            'length' => 2,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '限定？',
            'precision' => null,
        ],
        'search_snippet' => [
            'type' => 'text',
            'length' => 16777215,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'フリーワード検索用のスニペット',
            'precision' => null,
        ],
        'created' => [
            'type' => 'datetime',
            'length' => null,
            'precision' => null,
            'null' => true,
            'default' => null,
            'comment' => '作成日時',
        ],
        'modified' => [
            'type' => 'datetime',
            'length' => null,
            'precision' => null,
            'null' => true,
            'default' => null,
            'comment' => '更新日時',
        ],
        'deleted' => [
            'type' => 'datetime',
            'length' => null,
            'precision' => null,
            'null' => true,
            'default' => null,
            'comment' => '削除日時',
        ],
        '_indexes' => [
            'character_id' => [
                'type' => 'index',
                'columns' => [
                    'character_id',
                ],
                'length' => [
                ],
            ],
        ],
        '_constraints' => [
            'primary' => [
                'type' => 'primary',
                'columns' => [
                    'id',
                ],
                'length' => [
                ],
            ],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ],
    ];

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'character_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'rarity' => '01',
                'type' => '01',
                'add_date' => '2021-01-18',
                'gasha_include' => 1,
                'limited' => '01',
                'search_snippet' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2021-01-18 20:27:24',
                'modified' => '2021-01-18 20:27:24',
                'deleted' => null,
            ],
        ];
        parent::init();
    }
}
