<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {

        $this->table('admins')
            ->addColumn('mail', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('privilege', 'json', [
                'comment' => '権限',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('delete_flag', 'string', [
                'default' => '0',
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $this->table('card_reprints')
            ->addColumn('gasha_id', 'integer', [
                'comment' => 'ガシャID',
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('card_id', 'integer', [
                'comment' => 'カードID',
                'default' => null,
                'limit' => 11,
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
            ->addColumn('delete_flag', 'string', [
                'comment' => '削除フラグ',
                'default' => '0',
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $this->table('cards')
            ->addColumn('character_id', 'integer', [
                'comment' => 'キャラクター',
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'comment' => 'カード名',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('rarity', 'string', [
                'comment' => 'レアリティ',
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('type', 'string', [
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
            ->addColumn('gasha_include', 'tinyinteger', [
                'comment' => 'ガシャ限定？',
                'default' => '1',
                'limit' => 4,
                'null' => true,
            ])
            ->addColumn('limited', 'tinyinteger', [
                'comment' => '限定？',
                'default' => '0',
                'limit' => 4,
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
            ->addColumn('delete_flag', 'string', [
                'comment' => '削除フラグ',
                'default' => '0',
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $this->table('characters')
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
            ->addColumn('delete_flag', 'string', [
                'comment' => '削除フラグ',
                'default' => '0',
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $this->table('gasha_pickups')
            ->addColumn('gasha_id', 'integer', [
                'comment' => 'ガシャID',
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('card_id', 'integer', [
                'comment' => 'カードID',
                'default' => null,
                'limit' => 11,
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
            ->addColumn('delete_flag', 'string', [
                'comment' => '削除フラグ',
                'default' => '0',
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $this->table('gashas')
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
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('sr_rate', 'integer', [
                'comment' => 'SRレート',
                'default' => null,
                'limit' => 11,
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
            ->addColumn('delete_flag', 'string', [
                'comment' => '削除フラグ',
                'default' => '0',
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $this->table('operation_logs')
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
            ])
            ->addColumn('response_time', 'datetime', [
                'comment' => 'レスポンス日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('operation_logs_daily')
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
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $this->table('operation_logs_hourly')
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
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $this->table('operation_logs_monthly')
            ->addColumn('target_ym', 'integer', [
                'comment' => '対象年月',
                'default' => null,
                'limit' => 6,
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
                'limit' => 11,
                'null' => false,
            ])
            ->create();
    }

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
