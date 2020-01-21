<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * Gashas Model
 *
 * @property \App\Model\Table\CardReprintsTable|\Cake\ORM\Association\HasMany $CardReprints
 * @property \App\Model\Table\GashaPickupsTable|\Cake\ORM\Association\HasMany $GashaPickups
 *
 * @method \App\Model\Entity\Gasha get($primaryKey, $options = [])
 * @method \App\Model\Entity\Gasha newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Gasha[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Gasha|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gasha|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gasha patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Gasha[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Gasha findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GashasTable extends AppTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('gashas');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');


        $this->hasMany('CardReprints', [
            'foreignKey' => 'gasha_id'
        ]);
        $this->hasMany('GashaPickups', [
            'foreignKey' => 'gasha_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmpty('title');

        $validator
            ->integer('ssr_rate')
            ->allowEmpty('ssr_rate');

        $validator
            ->integer('sr_rate')
            ->allowEmpty('sr_rate');

        return $validator;
    }

    /**
     * patchEntityのオーバーライド
     * ファイル項目、GoogleMap項目のJSON文字列を配列に変換する
     * {@inheritDoc}
     * @see \Cake\ORM\Table::patchEntity()
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = [])
    {
        return parent::patchEntity($entity, $data, $options);
    }

    /**
     * CSVヘッダー情報を取得する
     */
    public function getCsvHeaders() {
        return [
            'ID',
            'ガシャ開始日',
            'ガシャ終了日',
            'ガシャタイトル',
            'SSRレート',
            'SRレート',
            '作成日時',
            '更新日時',
        ];
    }

    /**
     * CSVカラム情報を取得する
     */
    public function getCsvColumns() {
        return [
            'id',
            'start_date',
            'end_date',
            'title',
            'ssr_rate',
            'sr_rate',
            'created',
            'modified',
        ];
    }

    /**
     * CSVの入力情報を取得する
     */
    public function getCsvData($csv_row) {

        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // SSRレート
        $csv_data['ssr_rate'] = preg_replace('/[^0-9]/', '', $csv_data['ssr_rate']);
        // SRレート
        $csv_data['sr_rate'] = preg_replace('/[^0-9]/', '', $csv_data['sr_rate']);

        unset($csv_data['created']);
        unset($csv_data['modified']);
        return $csv_data;
    }

	/**
	 * ガシャ情報を取得
	 */
	public function findGashaData() {
		$query = $this->find();
		$gasha_data = $query->select([
				'id',
				'start_date',
				'end_date',
				'title',
				'ssr_rate',
				'sr_rate'
		])->enableHydration(false)->toArray();
		return $gasha_data;
	}

	/**
	 * ガシャ情報をjson形式で取得する
	 * @param unknown $gasha_datas
	 * @return Key: ガシャID、Value:ガシャ情報なjsonテキスト
	 */
	public function getGashaJsonData($gasha_datas = null) {
		if (is_null($gasha_datas)) {
			return null;
		}
		$json_data = [];
		foreach ($gasha_datas as $gasha_data) {
			$gasha_data['start_date'] = $gasha_data['start_date']->format('Y-m-d');
			$gasha_data['end_date'] = $gasha_data['end_date']->format('Y-m-d');
			$json_data[$gasha_data['id']] = $gasha_data;
		}
		return json_encode($json_data, JSON_UNESCAPED_UNICODE);
	}
}
