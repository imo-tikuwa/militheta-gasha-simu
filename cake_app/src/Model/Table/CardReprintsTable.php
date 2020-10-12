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
     * {@inheritDoc}
     * @see \Cake\ORM\Table::patchEntity()
     * @param EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        // フリーワード検索のスニペット更新
        $search_snippet = [];
        $gasha = TableRegistry::getTableLocator()->get('Gashas')->find()->select(['title'])->where(['id' => $data['gasha_id']])->first();
        if (!empty($gasha)) {
            $search_snippet[] = $gasha->title;
        }
        $card = TableRegistry::getTableLocator()->get('Cards')->find()->select(['name'])->where(['id' => $data['card_id']])->first();
        if (!empty($card)) {
            $search_snippet[] = $card->name;
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
     * CSVの入力情報を取得する
     * @param array $csv_row CSVの1行辺りの配列データ
     * @return array データ登録用に変換した配列データ
     */
    public function getCsvData($csv_row)
    {
        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // ガシャID
        $gashas = TableRegistry::getTableLocator()->get('Gashas');
        $gasha_data = $gashas->find()->select(['id'])->where(['title' => $csv_data['gasha_id']])->first();
        if (!empty($gasha_data)) {
            $csv_data['gasha_id'] = (string)$gasha_data->id;
        } else {
            $csv_data['gasha_id'] = null;
        }
        // カードID
        $cards = TableRegistry::getTableLocator()->get('Cards');
        $card_data = $cards->find()->select(['id'])->where(['name' => $csv_data['card_id']])->first();
        if (!empty($card_data)) {
            $csv_data['card_id'] = (string)$card_data->id;
        } else {
            $csv_data['card_id'] = null;
        }
        unset($csv_data['created']);
        unset($csv_data['modified']);

        return $csv_data;
    }
}
