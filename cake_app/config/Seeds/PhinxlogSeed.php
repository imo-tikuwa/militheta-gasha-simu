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
                'version' => '20211017020806',
                'migration_name' => 'Initial',
                'start_time' => '2021-10-17 11:08:07',
                'end_time' => '2021-10-17 11:08:07',
                'breakpoint' => '0',
            ],
        ];

        $table = $this->table('phinxlog');
        $table->insert($data)->save();
    }
}
