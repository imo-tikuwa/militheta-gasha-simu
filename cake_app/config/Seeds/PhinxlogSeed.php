<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Phinxlog seed.
 */
class PhinxlogSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'version' => '20201110131338',
                'migration_name' => 'Initial',
                'start_time' => '2020-11-10 22:13:38',
                'end_time' => '2020-11-10 22:13:38',
                'breakpoint' => '0',
            ],
        ];

        $table = $this->table('phinxlog');
        $table->insert($data)->save();
    }
}
