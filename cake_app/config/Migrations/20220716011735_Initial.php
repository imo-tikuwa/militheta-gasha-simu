<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->table('admins', [
                'comment' => '管理者情報',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '名前',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('mail', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'メールアドレス',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'パスワード',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('use_otp', 'boolean', [
                'comment' => '二段階認証を使用する？',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('otp_secret', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '二段階認証用シークレットキー',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('privilege', 'json', [
                'comment' => '権限',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('api_token', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'OpenAPIトークン',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('card_reprints', [
                'comment' => '復刻情報',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('gasha_id', 'integer', [
                'comment' => 'ガシャID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('card_id', 'integer', [
                'comment' => 'カードID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('search_snippet', 'text', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'gasha_id',
                ]
            )
            ->addIndex(
                [
                    'card_id',
                ]
            )
            ->create();

        $this->table('cards', [
                'comment' => 'カード',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('character_id', 'integer', [
                'comment' => 'キャラクター',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'カード名',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('rarity', 'char', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'レアリティ',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('type', 'char', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'タイプ',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('add_date', 'date', [
                'comment' => '実装日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('gasha_include', 'boolean', [
                'comment' => 'ガシャ対象？',
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('limited', 'char', [
                'collation' => 'utf8mb4_bin',
                'comment' => '限定？',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('search_snippet', 'text', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->create();

        $this->table('characters', [
                'comment' => 'キャラクター',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '名前',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('gasha_pickups', [
                'comment' => 'ピックアップ情報',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('gasha_id', 'integer', [
                'comment' => 'ガシャID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('card_id', 'integer', [
                'comment' => 'カードID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('search_snippet', 'text', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'gasha_id',
                ]
            )
            ->addIndex(
                [
                    'card_id',
                ]
            )
            ->create();

        $this->table('gashas', [
                'comment' => 'ガシャ',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('start_date', 'date', [
                'comment' => 'ガシャ開始日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('end_date', 'date', [
                'comment' => 'ガシャ終了日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'ガシャタイトル',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('ssr_rate', 'integer', [
                'comment' => 'SSRレート',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('sr_rate', 'integer', [
                'comment' => 'SRレート',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('search_snippet', 'text', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('operation_logs', [
                'comment' => '操作ログ',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('client_ip', 'text', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'クライアントIP',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_agent', 'text', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'ユーザーエージェント',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('request_url', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'リクエストURL',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('request_time', 'datetime', [
                'comment' => 'リクエスト日時',
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 3,
                // 'scale' => 3
            ])
            ->addColumn('response_time', 'datetime', [
                'comment' => 'レスポンス日時',
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 3,
                // 'scale' => 3
            ])
            ->create();

        $this->table('operation_logs_daily', [
                'comment' => '操作ログの集計(日毎)',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('target_ymd', 'date', [
                'comment' => '対象日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('summary_type', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '集計タイプ',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('groupedby', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'グループ元',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('counter', 'integer', [
                'comment' => 'カウンタ',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('operation_logs_hourly', [
                'comment' => '操作ログの集計(1時間毎)',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('target_time', 'datetime', [
                'comment' => '対象日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('summary_type', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '集計タイプ',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('groupedby', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'グループ元',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('counter', 'integer', [
                'comment' => 'カウンタ',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('operation_logs_monthly', [
                'comment' => '操作ログの集計(月毎)',
                'collation' => 'utf8mb4_bin',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('target_ym', 'integer', [
                'comment' => '対象年月',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('summary_type', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '集計タイプ',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('groupedby', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'グループ元',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('counter', 'integer', [
                'comment' => 'カウンタ',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
        $this->table('admins')->drop()->save();
        $this->table('card_reprints')->drop()->save();
        $this->table('cards')->drop()->save();
        $this->table('characters')->drop()->save();
        $this->table('gasha_pickups')->drop()->save();
        $this->table('gashas')->drop()->save();
        $this->table('operation_logs')->drop()->save();
        $this->table('operation_logs_daily')->drop()->save();
        $this->table('operation_logs_hourly')->drop()->save();
        $this->table('operation_logs_monthly')->drop()->save();
    }
}
