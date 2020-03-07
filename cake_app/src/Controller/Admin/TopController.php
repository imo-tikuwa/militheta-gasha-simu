<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * Top Controller
 */
class TopController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $functions = _code('LeftSideMenu');
        foreach ($functions as $alias => $function) {
            $table = TableRegistry::getTableLocator()->get($function['controller']);

            // テーブルのデータ登録数を取得する
            $functions[$alias]['data_count'] = $table->find()->count();

            // コントローラにCSVエクスポート機能があるかをチェックする
            $functions[$alias]['exist_csv_export'] = method_exists("App\\Controller\\Admin\\{$function['controller']}Controller", 'csvExport');
        }
        $this->set(compact('functions'));
    }
}
