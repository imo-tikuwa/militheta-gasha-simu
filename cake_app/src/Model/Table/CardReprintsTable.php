<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * CardReprints Model
 *
 * @property \App\Model\Table\GashasTable&\Cake\ORM\Association\BelongsTo $Gashas
 * @property \App\Model\Table\CardsTable&\Cake\ORM\Association\BelongsTo $Cards
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
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Cards', [
            'foreignKey' => 'card_id',
            'joinType' => 'INNER',
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
                'last' => true,
            ])
            ->add('gasha_id', 'existForeignEntity', [
                'rule' => function ($gasha_id) {
                    $table = TableRegistry::getTableLocator()->get('Gashas');
                    $entity = $table->find()->select(['id'])->where(['id' => $gasha_id])->first();

                    return !empty($entity);
                },
                'message' => 'ガシャIDに不正な値が入力されています。',
                'last' => true,
            ])
            ->notEmptyString('gasha_id', 'ガシャIDを選択してください。');

        // カードID
        $validator
            ->requirePresence('card_id', true, 'カードIDを選択してください。')
            ->add('card_id', 'integer', [
                'rule' => 'isInteger',
                'message' => 'カードIDを正しく入力してください。',
                'last' => true,
            ])
            ->add('card_id', 'existForeignEntity', [
                'rule' => function ($card_id) {
                    $table = TableRegistry::getTableLocator()->get('Cards');
                    $entity = $table->find()->select(['id'])->where(['id' => $card_id])->first();

                    return !empty($entity);
                },
                'message' => 'カードIDに不正な値が入力されています。',
                'last' => true,
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
     * @param \Cake\Datasource\EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     * @return \App\Model\Entity\CardReprint
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        // フリーワード検索のスニペット更新
        $search_snippet = [];
        if (isset($data['gasha_id'])) {
            $gasha = TableRegistry::getTableLocator()->get('Gashas')->find()->select(['title'])->where(['id' => $data['gasha_id']])->first();
            if (!empty($gasha)) {
                assert($gasha instanceof \App\Model\Entity\Gasha);
                $search_snippet[] = $gasha->title;
            }
        }
        if (isset($data['card_id'])) {
            $card = TableRegistry::getTableLocator()->get('Cards')->find()->select(['name'])->where(['id' => $data['card_id']])->first();
            if (!empty($card)) {
                assert($card instanceof \App\Model\Entity\Card);
                $search_snippet[] = $card->name;
            }
        }
        $data['search_snippet'] = implode(' ', array_unique($search_snippet));

        $entity = parent::patchEntity($entity, $data, $options);
        assert($entity instanceof \App\Model\Entity\CardReprint);

        return $entity;
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     *
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    public function getSearchQuery($request)
    {
        $query = $this->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->aliasField('id') => $request['id']]);
        }
        // ガシャID
        if (isset($request['gasha_id']) && !is_null($request['gasha_id']) && $request['gasha_id'] !== '') {
            $query->where(['Gashas.id' => $request['gasha_id']]);
        }
        // カードID
        if (isset($request['card_id']) && !is_null($request['card_id']) && $request['card_id'] !== '') {
            $query->where(['Cards.id' => $request['card_id']]);
        }
        // フリーワード
        if (isset($request['search_snippet']) && !is_null($request['search_snippet']) && $request['search_snippet'] !== '') {
            $search_snippet_conditions = [];
            foreach (explode(' ', str_replace('　', ' ', $request['search_snippet'])) as $search_snippet) {
                $search_snippet_conditions[] = [$this->aliasField('search_snippet LIKE') => "%{$search_snippet}%"];
            }
            if (isset($request['search_snippet_format']) && $request['search_snippet_format'] == 'AND') {
                $query->where($search_snippet_conditions);
            } else {
                $query->where(function ($exp) use ($search_snippet_conditions) {
                    return $exp->or($search_snippet_conditions);
                });
            }
        }
        $query->group('CardReprints.id');

        return $query->contain(['Gashas', 'Cards']);
    }

    /**
     * CSVヘッダー情報を取得する
     *
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
     *
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
     *
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
