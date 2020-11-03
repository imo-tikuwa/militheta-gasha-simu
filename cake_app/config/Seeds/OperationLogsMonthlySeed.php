<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * OperationLogsMonthly seed.
 */
class OperationLogsMonthlySeed extends AbstractSeed
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
                'id' => '20',
                'target_ym' => '202002',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '102',
            ],
            [
                'id' => '21',
                'target_ym' => '202002',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '102',
            ],
            [
                'id' => '22',
                'target_ym' => '202002',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '102',
            ],
            [
                'id' => '23',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/',
                'counter' => '4',
            ],
            [
                'id' => '24',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/target-pick',
                'counter' => '7',
            ],
            [
                'id' => '25',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/api/get-provision-ratio/121',
                'counter' => '1',
            ],
            [
                'id' => '26',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/api/target-pick-gasha/jyuren',
                'counter' => '2',
            ],
            [
                'id' => '27',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/api/get-provision-ratio/1',
                'counter' => '1',
            ],
            [
                'id' => '28',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/cake3-admin-baker',
                'counter' => '1',
            ],
            [
                'id' => '29',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/auth/login',
                'counter' => '8',
            ],
            [
                'id' => '30',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/top',
                'counter' => '4',
            ],
            [
                'id' => '31',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas',
                'counter' => '13',
            ],
            [
                'id' => '32',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/characters',
                'counter' => '1',
            ],
            [
                'id' => '33',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards',
                'counter' => '6',
            ],
            [
                'id' => '34',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/card-reprints',
                'counter' => '1',
            ],
            [
                'id' => '35',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gasha-pickups',
                'counter' => '17',
            ],
            [
                'id' => '36',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gasha-pickups/add',
                'counter' => '20',
            ],
            [
                'id' => '37',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/view/1',
                'counter' => '1',
            ],
            [
                'id' => '38',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/edit/1',
                'counter' => '4',
            ],
            [
                'id' => '39',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/access-logs',
                'counter' => '6',
            ],
            [
                'id' => '40',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards/csv-export',
                'counter' => '1',
            ],
            [
                'id' => '41',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards/csv-import',
                'counter' => '1',
            ],
            [
                'id' => '42',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/csv-export',
                'counter' => '1',
            ],
            [
                'id' => '43',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/csv-import',
                'counter' => '1',
            ],
            [
                'id' => '44',
                'target_ym' => '202002',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/add',
                'counter' => '1',
            ],
        ];

        $table = $this->table('operation_logs_monthly');
        $table->truncate();
        $table->insert($data)->save();
    }
}
