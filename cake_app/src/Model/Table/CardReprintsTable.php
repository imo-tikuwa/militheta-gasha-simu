<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * CardReprints Model
 *
 * @property \App\Model\Table\GashasTable&\Cake\ORM\Association\BelongsTo $Gashas
 * @property \App\Model\Table\CardsTable&\Cake\ORM\Association\BelongsTo $Cards
 *
 * @method \App\Model\Entity\CardReprint newEmptyEntity()
 * @method \App\Model\Entity\CardReprint newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CardReprint[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CardReprint get($primaryKey, $options = [])
 * @method \App\Model\Entity\CardReprint findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CardReprint patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CardReprint[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CardReprint|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CardReprint saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CardReprint[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CardReprint[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CardReprint[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CardReprint[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CardReprintsTable extends AppTable
{
    /** 論理削除を行う */
    use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('card_reprints');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Gashas', [
            'foreignKey' => 'gasha_id',
        ]);
        $this->belongsTo('Cards', [
            'foreignKey' => 'card_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        // ガシャID
        $validator
            ->requirePresence('gasha_id', true, 'ガシャIDを選択してください。')
            ->add('gasha_id', 'integer', [
                'rule' => 'isInteger',
                'message' => 'ガシャIDを正しく入力してください。',
                'last' => true
            ])
            ->add('gasha_id', 'existForeignEntity', [
                'rule' => function ($gasha_id) {
                    $table = TableRegistry::getTableLocator()->get('Gashas');
                    $entity = $table->find()->select(['id'])->where(['id' => $gasha_id])->first();
                    return !empty($entity);
                },
                'message' => 'ガシャIDに不正な値が入力されています。',
                'last' => true
            ])
            ->notEmptyString('gasha_id', 'ガシャIDを選択してください。');

        // カードID
        $validator
            ->requirePresence('card_id', true, 'カードIDを選択してください。')
            ->add('card_id', 'integer', [
                'rule' => 'isInteger',
                'message' => 'カードIDを正しく入力してください。',
                'last' => true
            ])
            ->add('card_id', 'existForeignEntity', [
                'rule' => function ($card_id) {
                    $table = TableRegistry::getTableLocator()->get('Cards');
                    $entity = $table->find()->select(['id'])->where(['id' => $card_id])->first();
                    return !empty($entity);
                },
                'message' => 'カードIDに不正な値が入力されています。',
                'last' => true
            ])
            ->notEmptyString('card_id', 'カードIDを選択してください。');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['gasha_id'], 'Gashas'));
        $rules->add($rules->existsIn(['card_id'], 'Cards'));

        return $rules;
    }

    /**
     * patchEntityのオーバーライド
     * ファイル項目、GoogleMap項目のJSON文字列を配列に変換する
     *
     * @see \Cake\ORM\Table::patchEntity()
     * @param EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     * @return \Cake\Datasource\EntityInterface
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        // フリーワード検索のスニペット更新
        $search_snippet = [];
        if (isset($data['gasha_id'])) {
            $gasha = TableRegistry::getTableLocator()->get('Gashas')->find()->select(['title'])->where(['id' => $data['gasha_id']])->first();
            if (!empty($gasha)) {
                $search_snippet[] = $gasha->title;
            }
        }
        if (isset($data['card_id'])) {
            $card = TableRegistry::getTableLocator()->get('Cards')->find()->select(['name'])->where(['id' => $data['card_id']])->first();
            if (!empty($card)) {
                $search_snippet[] = $card->name;
            }
        }
        $data['search_snippet'] = implode(' ', $search_snippet);

        return parent::patchEntity($entity, $data, $options);
    }

    /**
     * CSVヘッダー情報を取得する
     * @return array
     */
    public function getCsvHeaders()
    {
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
     * @return array
     */
    public function getCsvColumns()
    {
        return [
            'id',
            'gasha_id',
            'card_id',
            'created',
            'modified',
        ];
    }

    /**
     * Excelカラム情報を取得する
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'id',
            'gasha_id',
            'card_id',
            'created',
            'modified',
        ];
    }
}
