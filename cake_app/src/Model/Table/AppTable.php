<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use App\Model\Table\DeleteType;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
class AppTable extends Table {

	public function initialize(array $config) {
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

	// TODO 削除フラグ関係
	/**
	 * 検索前処理
	 * @param $event
	 * @param $query
	 * @param $options
	 * @param $primary
	 */
	public function beforeFind($event, $query, $options, $primary) {
		if ($this->checkDeleteFlag) {
			$query->where([$this->aliasField('delete_flag') => 0]);
		}
	}

	/** 削除フラグを参照するかどうかのフラグ */
	protected $checkDeleteFlag = true;

	/**
	 * 論理削除データを検索結果に含めないようにする（デフォルト）
	 */
	public function setCheckDeleteFlag() {
		$this->checkDeleteFlag = true;
	}

	/**
	 * 論理削除データを検索結果に含めるようにする
	 */
	public function setNotCheckDeleteFlag() {
		$this->checkDeleteFlag = false;
	}


	// TODO 削除処理関係
	/**
	 * 削除処理
	 * @param string $id
	 * @param string $delete_type
	 * @return boolean
	 */
	public function deleteRecord($id = '', $delete_type = DeleteType::LOGICAL) {
		if (empty($id)) {
			return false;
		}
		return $this->deleteRecords([$id], $delete_type);
	}

	/**
	 * 削除処理
	 * @param array $ids
	 * @param string $delete_type
	 * @return boolean
	 */
	public function deleteRecords($ids = [], $delete_type = DeleteType::LOGICAL) {
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
	 * @param string $table_class_name
	 * @param string $display_column
	 * @param boolean $add_empty_selection 空の選択肢を加えるか
	 * @throws \Exception
	 * @return NULL
	 */
	public function findForeignSelectionData($table_class_name = null, $display_column = 'id', $add_empty_selection = false) {
		if (is_null($table_class_name)) {
			throw new \Exception();
		}
		$table = TableRegistry::getTableLocator()->get($table_class_name);
		$list = $table->find()->select(['id', $display_column])->enableHydration(false)->toArray();
		if (!empty($list)) {
			$list = Hash::combine($list, '{n}.id', "{n}.{$display_column}");
			if ($add_empty_selection) {
				$list = ["" => "　"] + $list;
			}
			return $list;
		}
		return null;
	}
}