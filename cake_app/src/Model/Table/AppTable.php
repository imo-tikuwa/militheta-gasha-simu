<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class AppTable extends Table
{

    /**
     * 初期化処理
     * {@inheritDoc}
     * @see \Cake\ORM\Table::initialize()
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        /** 作成日時、更新日時の自動付与 */
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
    }

    /**
     * 選択肢情報を取得する
     * @param string $table_class_name テーブルクラス名
     * @param string $display_column 画面表示する値を持つカラム名
     * @param bool $add_empty_selection 空の選択肢を加えるか
     * @throws \Exception
     * @return NULL
     */
    public function findForeignSelectionData($table_class_name = null, $display_column = 'id', $add_empty_selection = false)
    {
        if (is_null($table_class_name)) {
            throw new \Exception();
        }
        $table = TableRegistry::getTableLocator()->get($table_class_name);
        $list = $table->find()->select(['id', $display_column])->enableHydration(false)->toArray();
        if (!empty($list)) {
            $list = Hash::combine($list, '{n}.id', "{n}." . $display_column);
            if ($add_empty_selection) {
                $list = ["" => "　"] + $list;
            }

            return $list;
        }

        return null;
    }
}
