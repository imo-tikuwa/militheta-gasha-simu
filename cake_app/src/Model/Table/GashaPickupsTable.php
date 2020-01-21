<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * GashaPickups Model
 *
 * @property \App\Model\Table\GashasTable|\Cake\ORM\Association\BelongsTo $Gashas
 * @property \App\Model\Table\CardsTable|\Cake\ORM\Association\BelongsTo $Cards
 *
 * @method \App\Model\Entity\GashaPickup get($primaryKey, $options = [])
 * @method \App\Model\Entity\GashaPickup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GashaPickup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GashaPickup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GashaPickup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GashaPickup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GashaPickup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GashaPickup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GashaPickupsTable extends AppTable
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

        $this->setTable('gasha_pickups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');


        $this->belongsTo('Gashas', [
            'foreignKey' => 'gasha_id'
        ]);
        $this->belongsTo('Cards', [
            'foreignKey' => 'card_id'
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

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['gasha_id'], 'Gashas'));
        $rules->add($rules->existsIn(['card_id'], 'Cards'));

        return $rules;
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
            'ガシャID',
            'カードID',
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
            'gasha_id',
            'card_id',
            'created',
            'modified',
        ];
    }

    /**
     * CSVの入力情報を取得する
     */
    public function getCsvData($csv_row) {

        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // ガシャID
        $gashas = TableRegistry::getTableLocator()->get('Gashas');
        $gasha_data = $gashas->find()->select(['id'])->where(['title' => $csv_data['gasha_id']])->first();
        if (!empty($gasha_data)) {
            $csv_data['gasha_id'] = (string) $gasha_data->id;
        } else {
            $csv_data['gasha_id'] = null;
        }
        // カードID
        $cards = TableRegistry::getTableLocator()->get('Cards');
        $card_data = $cards->find()->select(['id'])->where(['name' => $csv_data['card_id']])->first();
        if (!empty($card_data)) {
            $csv_data['card_id'] = (string) $card_data->id;
        } else {
            $csv_data['card_id'] = null;
        }

        unset($csv_data['created']);
        unset($csv_data['modified']);
        return $csv_data;
    }
}