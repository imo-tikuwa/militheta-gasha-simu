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
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('mail', 'string', [
                'comment' => 'メールアドレス',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'comment' => 'パスワード',
                'default' => null,
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
                'comment' => '二段階認証用シークレットキー',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('privilege', 'json', [
                'comment' => '権限',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
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
                'null' => true,
            ])
            ->addColumn('card_id', 'integer', [
                'comment' => 'カードID',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('search_snippet', 'text', [
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'card_id',
                ]
            )
            ->addIndex(
                [
                    'gasha_id',
                ]
            )
            ->create();

        $this->table('cards', [
                'comment' => 'カード',
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
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'comment' => 'カード名',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('rarity', 'char', [
                'comment' => 'レアリティ',
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('type', 'char', [
                'comment' => 'タイプ',
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('add_date', 'date', [
                'comment' => '実装日',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('gasha_include', 'boolean', [
                'comment' => 'ガシャ対象？',
                'default' => true,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('limited', 'char', [
                'comment' => '限定？',
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('search_snippet', 'text', [
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
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
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('gasha_pickups', [
                'comment' => 'ピックアップ情報',
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
                'null' => true,
            ])
            ->addColumn('card_id', 'integer', [
                'comment' => 'カードID',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('search_snippet', 'text', [
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'card_id',
                ]
            )
            ->addIndex(
                [
                    'gasha_id',
                ]
            )
            ->create();

        $this->table('gashas', [
                'comment' => 'ガシャ',
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
                'null' => true,
            ])
            ->addColumn('end_date', 'date', [
                'comment' => 'ガシャ終了日',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('title', 'string', [
                'comment' => 'ガシャタイトル',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('ssr_rate', 'integer', [
                'comment' => 'SSRレート',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('sr_rate', 'integer', [
                'comment' => 'SRレート',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('search_snippet', 'text', [
                'comment' => 'フリーワード検索用のスニペット',
                'default' => null,
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
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
                'comment' => 'クライアントIP',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_agent', 'text', [
                'comment' => 'ユーザーエージェント',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('request_url', 'string', [
                'comment' => 'リクエストURL',
                'default' => null,
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
                'comment' => '集計タイプ',
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('groupedby', 'string', [
                'comment' => 'グループ元',
                'default' => null,
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
                'comment' => '集計タイプ',
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('groupedby', 'string', [
                'comment' => 'グループ元',
                'default' => null,
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
                'comment' => '集計タイプ',
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('groupedby', 'string', [
                'comment' => 'グループ元',
                'default' => null,
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
