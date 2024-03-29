<?php
declare(strict_types=1);

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
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => '管理者',
                'mail' => 'admin@imo-tikuwa.com',
                'password' => 'ExkD1/9lm1NHZZt2+phf5w==:QgOAS4fgkguFQ9jZYHL9ow==',
                'use_otp' => false,
                'otp_secret' => NULL,
                'privilege' => NULL,
                'api_token' => NULL,
                'created' => '2020-10-07 16:13:05',
                'modified' => '2020-10-07 16:13:05',
                'deleted' => NULL,
            ],
            [
                'id' => 2,
                'name' => '一般ユーザー',
                'mail' => 'user1@imo-tikuwa.com',
                'password' => 'yerFNCGATtp9Cb+O3gAECg==:KJExaS9/APeHbcirsqZVYg==',
                'use_otp' => false,
                'otp_secret' => NULL,
                'privilege' => '{"Cards":["READ","CSV_EXPORT","EXCEL_EXPORT"],"Gashas":["READ"],"Characters":["READ"],"CardReprints":["READ"],"GashaPickups":["READ"]}',
                'api_token' => NULL,
                'created' => '2020-10-07 16:14:02',
                'modified' => '2020-11-24 18:31:29',
                'deleted' => NULL,
            ],
        ];

        $table = $this->table('admins');
        $table->insert($data)->save();
    }
}
