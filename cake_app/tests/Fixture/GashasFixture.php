<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GashasFixture
 */
class GashasFixture extends TestFixture
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
                'start_date' => '2022-05-28',
                'end_date' => '2022-05-28',
                'title' => 'Lorem ipsum dolor sit amet',
                'ssr_rate' => 0,
                'sr_rate' => 0,
                'search_snippet' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2022-05-28 16:56:09',
                'modified' => '2022-05-28 16:56:09',
                'deleted' => null,
            ],
        ];
        parent::init();
    }
}
