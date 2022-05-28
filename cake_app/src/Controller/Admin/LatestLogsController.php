<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\ORM\TableRegistry;

/**
 * operation_logsの直近のデータを一覧表示するだけのコントローラ
 * エンティティのアクセサの動作確認用
 *
 * @property \OperationLogs\Model\Table\OperationLogsTable $OperationLogs
 */
class LatestLogsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        // OperationLogsプラグインのOperationLogsテーブルクラス
        $this->OperationLogs = TableRegistry::getTableLocator()->get('OperationLogs.OperationLogs');

        // 直近20件を取得
        $operation_logs = $this->OperationLogs->find()->orderDesc('id')->limit(20)->toArray();

        $this->set(compact('operation_logs'));
    }
}
