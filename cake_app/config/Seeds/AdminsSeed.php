<?php
use Migrations\AbstractSeed;

/**
 * Admins seed.
 */
class AdminsSeed extends AbstractSeed
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
                'mail' => 'admin@imo-tikuwa.com',
                'password' => 'lbV+4PEvS9UyIMnM5IbqzQ==:yFiuLMAJMrQO6gsizK5y0w==',
                'privilege' => NULL,
                'created' => '2020-01-03 22:24:33',
                'modified' => '2020-01-03 22:24:33',
                'delete_flag' => '0',
            ],
            [
                'id' => '2',
                'mail' => 'user1@imo-tikuwa.com',
                'password' => 'Q5mYbUIO2H/QXvaVOzrfWQ==:5hllsFixcuGYt5n6HjkoHg==',
                'privilege' => '{"Cards": ["READ"], "Gashas": ["READ"], "Characters": ["READ"], "CardReprints": ["READ"], "GashaPickups": ["READ"]}',
                'created' => '2020-03-12 19:22:35',
                'modified' => '2020-03-12 19:22:35',
                'delete_flag' => '0',
            ],
        ];

        $table = $this->table('admins');
        $table->truncate();
        $table->insert($data)->save();
    }
}
