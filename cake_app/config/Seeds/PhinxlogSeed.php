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
                'version' => '20200427082454',
                'migration_name' => 'Initial',
                'start_time' => '2020-04-27 08:24:56',
                'end_time' => '2020-04-27 08:24:56',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200427082950',
                'migration_name' => 'Initial',
                'start_time' => '2020-04-27 08:29:50',
                'end_time' => '2020-04-27 08:29:50',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200429002901',
                'migration_name' => 'Initial',
                'start_time' => '2020-04-29 00:29:02',
                'end_time' => '2020-04-29 00:29:02',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200527234325',
                'migration_name' => 'Initial',
                'start_time' => '2020-05-27 23:43:26',
                'end_time' => '2020-05-27 23:43:26',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200530045846',
                'migration_name' => 'Initial',
                'start_time' => '2020-05-30 04:58:47',
                'end_time' => '2020-05-30 04:58:47',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200701093800',
                'migration_name' => 'Initial',
                'start_time' => '2020-07-01 09:38:01',
                'end_time' => '2020-07-01 09:38:01',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200801020321',
                'migration_name' => 'Initial',
                'start_time' => '2020-08-01 02:03:22',
                'end_time' => '2020-08-01 02:03:22',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200831113822',
                'migration_name' => 'Initial',
                'start_time' => '2020-08-31 11:38:23',
                'end_time' => '2020-08-31 11:38:23',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200929112922',
                'migration_name' => 'Initial',
                'start_time' => '2020-09-29 11:29:23',
                'end_time' => '2020-09-29 11:29:23',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200929114832',
                'migration_name' => 'Initial',
                'start_time' => '2020-09-29 11:48:32',
                'end_time' => '2020-09-29 11:48:32',
                'breakpoint' => '0',
            ],
            [
                'version' => '20200929115423',
                'migration_name' => 'Initial',
                'start_time' => '2020-09-29 11:54:23',
                'end_time' => '2020-09-29 11:54:23',
                'breakpoint' => '0',
            ],
            [
                'version' => '20201003000049',
                'migration_name' => 'Initial',
                'start_time' => '2020-10-03 00:00:49',
                'end_time' => '2020-10-03 00:00:49',
                'breakpoint' => '0',
            ],
            [
                'version' => '20201007070336',
                'migration_name' => 'Initial',
                'start_time' => '2020-10-07 07:03:36',
                'end_time' => '2020-10-07 07:03:36',
                'breakpoint' => '0',
            ],
            [
                'version' => '20201007071447',
                'migration_name' => 'Initial',
                'start_time' => '2020-10-07 07:14:48',
                'end_time' => '2020-10-07 07:14:48',
                'breakpoint' => '0',
            ],
            [
                'version' => '20201103061532',
                'migration_name' => 'Initial',
                'start_time' => '2020-11-03 15:15:32',
                'end_time' => '2020-11-03 15:15:32',
                'breakpoint' => '0',
            ],
        ];

        $table = $this->table('phinxlog');
        $table->truncate();
        $table->insert($data)->save();
    }
}
