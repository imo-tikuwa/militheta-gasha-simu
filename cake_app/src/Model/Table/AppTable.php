<?php
namespace App\Model\Table;

use App\Model\Table\DeleteType;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class AppTable extends Table
{

    /**
     * 初期化処理
     * {@inheritDoc}
     * @see \Cake\ORM\Table::initialize()
     */
    public function initialize(array $config)
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
     * 検索前処理
     * @param Event $event event object.
     * @param Query $query query object.
     * @param array $options option array.
     * @param bool $primary root query or association query.
     * @return void
     */
    public function beforeFind(Event $event, Query $query, $options, $primary)
    {
        if ($this->checkDeleteFlag) {
            $query->where([$this->aliasField('delete_flag') => 0]);
        }
    }

    /** 削除フラグを参照するかどうかのフラグ */
    protected $checkDeleteFlag = true;

    /**
     * 論理削除データを検索結果に含めないようにする（デフォルト）
     * @return void
     */
    public function setCheckDeleteFlag()
    {
        $this->checkDeleteFlag = true;
    }

    /**
     * 論理削除データを検索結果に含めるようにする
     * @return void
     */
    public function setNotCheckDeleteFlag()
    {
        $this->checkDeleteFlag = false;
    }

    /**
     * 削除処理
     * @param string $id テーブルのプライマリキーの値
     * @param string $delete_type 論理削除(logical) or 物理削除(physical)
     * @return bool
     */
    public function deleteRecord($id = '', $delete_type = DeleteType::LOGICAL)
    {
        if (empty($id)) {
            return false;
        }

        return $this->deleteRecords([$id], $delete_type);
    }

    /**
     * 削除処理
     * @param array $ids テーブルのプライマリキーの値
     * @param string $delete_type 論理削除(logical) or 物理削除(physical)
     * @return bool
     */
    public function deleteRecords($ids = [], $delete_type = DeleteType::LOGICAL)
    {
        if (empty($ids)) {
            return false;
        }
        $entities = $this->find()->where(['id IN' => $ids])->toArray();
        foreach ($entities as $entity) {
            if (DeleteType::PHYSICAL == $delete_type) {
                // 物理削除
                parent::delete($entity);
            } else {
                // 論理削除
                $entity = $this->patchEntity($entity, ['delete_flag' => 1], ['validate' => false]);
                $this->save($entity);
            }
        }

        return true;
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
