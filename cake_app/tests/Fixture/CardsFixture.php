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
                'add_date' => '2022-05-28',
                'gasha_include' => 1,
                'limited' => '01',
                'search_snippet' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2022-05-28 16:56:05',
                'modified' => '2022-05-28 16:56:05',
                'deleted' => null,
            ],
        ];
        parent::init();
    }
}
