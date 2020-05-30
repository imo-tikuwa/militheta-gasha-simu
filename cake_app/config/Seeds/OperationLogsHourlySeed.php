<?php
use Migrations\AbstractSeed;

/**
 * OperationLogsHourly seed.
 */
class OperationLogsHourlySeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '4',
            ],
            [
                'id' => '2',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '4',
            ],
            [
                'id' => '3',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '4',
            ],
            [
                'id' => '4',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'url',
                'groupedby' => '/',
                'counter' => '1',
            ],
            [
                'id' => '5',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'url',
                'groupedby' => '/target-pick',
                'counter' => '1',
            ],
            [
                'id' => '6',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'url',
                'groupedby' => '/api/get-provision-ratio/121',
                'counter' => '1',
            ],
            [
                'id' => '7',
                'target_time' => '2020-02-10 19:00:00',
                'summary_type' => 'url',
                'groupedby' => '/api/target-pick-gasha/jyuren',
                'counter' => '1',
            ],
            [
                'id' => '8',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '13',
            ],
            [
                'id' => '9',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '13',
            ],
            [
                'id' => '10',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '13',
            ],
            [
                'id' => '11',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/target-pick',
                'counter' => '2',
            ],
            [
                'id' => '12',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/api/get-provision-ratio/1',
                'counter' => '1',
            ],
            [
                'id' => '13',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/api/target-pick-gasha/jyuren',
                'counter' => '1',
            ],
            [
                'id' => '14',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/cake3-admin-baker',
                'counter' => '1',
            ],
            [
                'id' => '15',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/auth/login',
                'counter' => '2',
            ],
            [
                'id' => '16',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/top',
                'counter' => '1',
            ],
            [
                'id' => '17',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas',
                'counter' => '2',
            ],
            [
                'id' => '18',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/characters',
                'counter' => '1',
            ],
            [
                'id' => '19',
                'target_time' => '2020-02-10 20:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards',
                'counter' => '2',
            ],
            [
                'id' => '20',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '17',
            ],
            [
                'id' => '21',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '17',
            ],
            [
                'id' => '22',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '17',
            ],
            [
                'id' => '23',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/auth/login',
                'counter' => '2',
            ],
            [
                'id' => '24',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/top',
                'counter' => '1',
            ],
            [
                'id' => '25',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/card-reprints',
                'counter' => '1',
            ],
            [
                'id' => '26',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gasha-pickups',
                'counter' => '1',
            ],
            [
                'id' => '27',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gasha-pickups/add',
                'counter' => '1',
            ],
            [
                'id' => '28',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas',
                'counter' => '4',
            ],
            [
                'id' => '29',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/view/1',
                'counter' => '1',
            ],
            [
                'id' => '30',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/edit/1',
                'counter' => '4',
            ],
            [
                'id' => '31',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/',
                'counter' => '1',
            ],
            [
                'id' => '32',
                'target_time' => '2020-02-12 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/target-pick',
                'counter' => '1',
            ],
            [
                'id' => '33',
                'target_time' => '2020-02-13 22:00:00',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '2',
            ],
            [
                'id' => '34',
                'target_time' => '2020-02-13 22:00:00',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '2',
            ],
            [
                'id' => '35',
                'target_time' => '2020-02-13 22:00:00',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '2',
            ],
            [
                'id' => '36',
                'target_time' => '2020-02-13 22:00:00',
                'summary_type' => 'url',
                'groupedby' => '/',
                'counter' => '2',
            ],
            [
                'id' => '37',
                'target_time' => '2020-02-17 13:00:00',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '9',
            ],
            [
                'id' => '38',
                'target_time' => '2020-02-17 13:00:00',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '9',
            ],
            [
                'id' => '39',
                'target_time' => '2020-02-17 13:00:00',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '9',
            ],
            [
                'id' => '40',
                'target_time' => '2020-02-17 13:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/auth/login',
                'counter' => '2',
            ],
            [
                'id' => '41',
                'target_time' => '2020-02-17 13:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/top',
                'counter' => '1',
            ],
            [
                'id' => '42',
                'target_time' => '2020-02-17 13:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/access-logs',
                'counter' => '6',
            ],
            [
                'id' => '43',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'all',
                'groupedby' => NULL,
                'counter' => '58',
            ],
            [
                'id' => '44',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'ip',
                'groupedby' => '192.168.1.3',
                'counter' => '58',
            ],
            [
                'id' => '45',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'ua',
                'groupedby' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0',
                'counter' => '58',
            ],
            [
                'id' => '46',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/auth/login',
                'counter' => '2',
            ],
            [
                'id' => '47',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/top',
                'counter' => '1',
            ],
            [
                'id' => '48',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards/csv-export',
                'counter' => '1',
            ],
            [
                'id' => '49',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards',
                'counter' => '4',
            ],
            [
                'id' => '50',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/cards/csv-import',
                'counter' => '1',
            ],
            [
                'id' => '51',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas',
                'counter' => '7',
            ],
            [
                'id' => '52',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/csv-export',
                'counter' => '1',
            ],
            [
                'id' => '53',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/csv-import',
                'counter' => '1',
            ],
            [
                'id' => '54',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gashas/add',
                'counter' => '1',
            ],
            [
                'id' => '55',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gasha-pickups',
                'counter' => '16',
            ],
            [
                'id' => '56',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/admin/gasha-pickups/add',
                'counter' => '19',
            ],
            [
                'id' => '57',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/',
                'counter' => '1',
            ],
            [
                'id' => '58',
                'target_time' => '2020-02-18 18:00:00',
                'summary_type' => 'url',
                'groupedby' => '/target-pick',
                'counter' => '3',
            ],
        ];

        $table = $this->table('operation_logs_hourly');
        $table->truncate();
        $table->insert($data)->save();
    }
}
