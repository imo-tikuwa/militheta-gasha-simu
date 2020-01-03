<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * Cards Model
 *
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 *
 * @method \App\Model\Entity\Card get($primaryKey, $options = [])
 * @method \App\Model\Entity\Card newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Card[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Card|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Card|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Card patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Card[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Card findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CardsTable extends AppTable
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

        $this->setTable('cards');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');


        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmpty('name');

        $validator
            ->scalar('rarity')
            ->maxLength('rarity', 2)
            ->allowEmpty('rarity');

        $validator
            ->scalar('type')
            ->maxLength('type', 2)
            ->allowEmpty('type');

        $validator
            ->date('add_date')
            ->allowEmpty('add_date');

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
        $rules->add($rules->existsIn(['character_id'], 'Characters'));

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
            'キャラクター',
            'カード名',
            'レアリティ',
            'タイプ',
            '実装日',
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
            'character_id',
            'name',
            'rarity',
            'type',
            'add_date',
            'created',
            'modified',
        ];
    }

    /**
     * CSVの入力情報を取得する
     */
    public function getCsvData($csv_row) {

        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // キャラクター
        $characters = TableRegistry::getTableLocator()->get('Characters');
        $character_data = $characters->find()->select(['id'])->where(['name' => $csv_data['character_id']])->first();
        if (!empty($character_data)) {
            $csv_data['character_id'] = (string) $character_data->id;
        } else {
            $csv_data['character_id'] = null;
        }
        // レアリティ
        $codes = array_flip(_code("Cards.rarity"));
        foreach ($codes as $code_value => $code_key) {
            if ($code_value === $csv_data['rarity']) {
                $csv_data['rarity'] = $code_key;
            }
        }
        // タイプ
        $codes = array_flip(_code("Cards.type"));
        foreach ($codes as $code_value => $code_key) {
            if ($code_value === $csv_data['type']) {
                $csv_data['type'] = $code_key;
            }
        }

        unset($csv_data['created']);
        unset($csv_data['modified']);
        return $csv_data;
    }
}
