<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CardReprintsFixture
 */
class CardReprintsFixture extends TestFixture
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
        'gasha_id' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => true,
            'default' => null,
            'comment' => 'ガシャID',
            'precision' => null,
            'autoIncrement' => null,
        ],
        'card_id' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => true,
            'default' => null,
            'comment' => 'カードID',
            'precision' => null,
            'autoIncrement' => null,
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
            'card_id' => [
                'type' => 'index',
                'columns' => [
                    'card_id',
                ],
                'length' => [
                ],
            ],
            'gasha_id' => [
                'type' => 'index',
                'columns' => [
                    'gasha_id',
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
                'gasha_id' => 1,
                'card_id' => 1,
                'search_snippet' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2021-01-18 20:27:12',
                'modified' => '2021-01-18 20:27:12',
                'deleted' => null,
            ],
        ];
        parent::init();
    }
}
