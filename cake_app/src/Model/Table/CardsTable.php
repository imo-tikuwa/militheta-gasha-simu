<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Gasha;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * Cards Model
 *
 * @property \App\Model\Table\CharactersTable&\Cake\ORM\Association\BelongsTo $Characters
 * @property \App\Model\Table\CardReprintsTable&\Cake\ORM\Association\HasMany $CardReprints
 * @property \App\Model\Table\GashaPickupsTable&\Cake\ORM\Association\HasMany $GashaPickups
 *
 * @method \App\Model\Entity\Card newEmptyEntity()
 * @method \App\Model\Entity\Card newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Card[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Card get($primaryKey, $options = [])
 * @method \App\Model\Entity\Card findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Card patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Card[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Card|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Card saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CardsTable extends AppTable
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

        $this->setTable('cards');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
        ]);
        $this->hasMany('CardReprints', [
            'foreignKey' => 'card_id',
        ]);
        $this->hasMany('GashaPickups', [
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

        // キャラクター
        $validator
            ->requirePresence('character_id', true, 'キャラクターを選択してください。')
            ->add('character_id', 'integer', [
                'rule' => 'isInteger',
                'message' => 'キャラクターを正しく入力してください。',
                'last' => true
            ])
            ->add('character_id', 'existForeignEntity', [
                'rule' => function ($character_id) {
                    $table = TableRegistry::getTableLocator()->get('Characters');
                    $entity = $table->find()->select(['id'])->where(['id' => $character_id])->first();
                    return !empty($entity);
                },
                'message' => 'キャラクターに不正な値が入力されています。',
                'last' => true
            ])
            ->notEmptyString('character_id', 'キャラクターを選択してください。');

        // カード名
        $validator
            ->requirePresence('name', true, 'カード名を入力してください。')
            ->add('name', 'scalar', [
                'rule' => 'isScalar',
                'message' => 'カード名を正しく入力してください。',
                'last' => true
            ])
            ->add('name', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => 'カード名は255文字以内で入力してください。',
                'last' => true
            ])
            ->notEmptyString('name', 'カード名を入力してください。');

        // レアリティ
        $validator
            ->requirePresence('rarity', true, 'レアリティを選択してください。')
            ->add('rarity', 'scalar', [
                'rule' => 'isScalar',
                'message' => 'レアリティを正しく入力してください。',
                'last' => true
            ])
            ->add('rarity', 'maxLength', [
                'rule' => ['maxLength', 2],
                'message' => 'レアリティは2文字以内で入力してください。',
                'last' => true
            ])
            ->add('rarity', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Cards.rarity'));
                },
                'message' => 'レアリティに不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('rarity', 'レアリティを選択してください。');

        // タイプ
        $validator
            ->requirePresence('type', true, 'タイプを選択してください。')
            ->add('type', 'scalar', [
                'rule' => 'isScalar',
                'message' => 'タイプを正しく入力してください。',
                'last' => true
            ])
            ->add('type', 'maxLength', [
                'rule' => ['maxLength', 2],
                'message' => 'タイプは2文字以内で入力してください。',
                'last' => true
            ])
            ->add('type', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Cards.type'));
                },
                'message' => 'タイプに不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('type', 'タイプを選択してください。');

        // 実装日
        $validator
            ->requirePresence('add_date', true, '実装日を入力してください。')
            ->add('add_date', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => '実装日を正しく入力してください。',
                'last' => true
            ])
            ->notEmptyDate('add_date', '実装日を入力してください。');

        // ガシャ対象？
        $validator
            ->requirePresence('gasha_include', true, 'ガシャ対象？を選択してください。')
            ->add('gasha_include', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Cards.gasha_include'));
                },
                'message' => 'ガシャ対象？に不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('gasha_include', 'ガシャ対象？を選択してください。');

        // 限定？
        $validator
            ->requirePresence('limited', true, '限定？を選択してください。')
            ->add('limited', 'scalar', [
                'rule' => 'isScalar',
                'message' => '限定？を正しく入力してください。',
                'last' => true
            ])
            ->add('limited', 'maxLength', [
                'rule' => ['maxLength', 2],
                'message' => '限定？は2文字以内で入力してください。',
                'last' => true
            ])
            ->add('limited', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Cards.limited'));
                },
                'message' => '限定？に不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('limited', '限定？を選択してください。');

        return $validator;
    }

    /**
     * CSV import validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationCsv(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

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
        $rules->add($rules->existsIn(['character_id'], 'Characters'));

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
     * @return \App\Model\Entity\Card
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        // フリーワード検索のスニペット更新
        $search_snippet = [];
        if (isset($data['character_id'])) {
            $character = TableRegistry::getTableLocator()->get('Characters')->find()->select(['name'])->where(['id' => $data['character_id']])->first();
            if (!empty($character)) {
                $search_snippet[] = $character->name;
            }
        }
        if (isset($data['name']) && $data['name'] != '') {
            $search_snippet[] = $data['name'];
        }
        if (isset($data['rarity']) && $data['rarity'] != '') {
            $search_snippet[] = _code("Codes.Cards.rarity.{$data['rarity']}");
        }
        if (isset($data['type']) && $data['type'] != '') {
            $search_snippet[] = _code("Codes.Cards.type.{$data['type']}");
        }
        if (isset($data['limited']) && $data['limited'] != '') {
            $search_snippet[] = _code("Codes.Cards.limited.{$data['limited']}");
        }
        $data['search_snippet'] = implode(' ', $search_snippet);

        return parent::patchEntity($entity, $data, $options);
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
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
        // キャラクター
        if (isset($request['character_id']) && !is_null($request['character_id']) && $request['character_id'] !== '') {
            $query->where(['Characters.id' => $request['character_id']]);
        }
        // カード名
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->aliasField('name LIKE') => "%{$request['name']}%"]);
        }
        // レアリティ
        if (isset($request['rarity']) && !is_null($request['rarity']) && $request['rarity'] !== '') {
            $query->where([$this->aliasField('rarity') => $request['rarity']]);
        }
        // タイプ
        if (isset($request['type']) && !is_null($request['type']) && $request['type'] !== '') {
            $query->where([$this->aliasField('type') => $request['type']]);
        }
        // 実装日
        if (isset($request['add_date']) && !is_null($request['add_date']) && $request['add_date'] !== '') {
            $query->where([$this->aliasField('add_date') => $request['add_date']]);
        }
        // ガシャ対象？
        if (isset($request['gasha_include']) && !is_null($request['gasha_include']) && $request['gasha_include'] !== '') {
            $query->where([$this->aliasField('gasha_include') => $request['gasha_include']]);
        }
        // 限定？
        if (isset($request['limited']) && !is_null($request['limited']) && $request['limited'] !== '') {
            $query->where([$this->aliasField('limited') => $request['limited']]);
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
        $query->group('Cards.id');

        return $query->contain(['Characters', 'CardReprints', 'GashaPickups']);
    }

    /**
     * CSVヘッダー情報を取得する
     * @return array
     */
    public function getCsvHeaders()
    {
        return [
            'ID',
            'キャラクター',
            'カード名',
            'レアリティ',
            'タイプ',
            '実装日',
            'ガシャ対象？',
            '限定？',
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
            'character_id',
            'name',
            'rarity',
            'type',
            'add_date',
            'gasha_include',
            'limited',
            'created',
            'modified',
        ];
    }

    /**
     * CSVの入力情報を元にエンティティを作成する
     * @param array $csv_row CSVの1行辺りの配列データ
     * @return \App\Model\Entity\Card エンティティ
     */
    public function createEntityByCsvRow($csv_row)
    {
        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // キャラクター
        $characters = TableRegistry::getTableLocator()->get('Characters');
        $character_data = $characters->find()->select(['id'])->where(['name' => $csv_data['character_id']])->first();
        if (!empty($character_data)) {
            $csv_data['character_id'] = (string)$character_data->id;
        } else {
            $csv_data['character_id'] = '';
        }
        // レアリティ
        $codes = array_flip(_code("Codes.Cards.rarity"));
        foreach ($codes as $code_value => $code_key) {
            if ($code_value === $csv_data['rarity']) {
                $csv_data['rarity'] = $code_key;
            }
        }
        // タイプ
        $codes = array_flip(_code("Codes.Cards.type"));
        foreach ($codes as $code_value => $code_key) {
            if ($code_value === $csv_data['type']) {
                $csv_data['type'] = $code_key;
            }
        }
        // ガシャ対象？
        $gasha_include = 0;
        if (array_key_exists($csv_data['gasha_include'], _code("Codes.Cards.gasha_include"))) {
            $gasha_include = $csv_data['gasha_include'];
        }
        $csv_data['gasha_include'] = $gasha_include;
        // 限定？
        $codes = array_flip(_code("Codes.Cards.limited"));
        foreach ($codes as $code_value => $code_key) {
            if ($code_value === $csv_data['limited']) {
                $csv_data['limited'] = $code_key;
            }
        }

        unset($csv_data['created']);
        unset($csv_data['modified']);

        // Csvの入力情報を元にエンティティを作成
        if (!empty($csv_data['id'])) {
            $card = $this->get($csv_data['id']);
            $this->touch($card);
        } else {
            $card = $this->newEmptyEntity();
        }
        $card = $this->patchEntity($card, $csv_data, ['validate' => 'csv']);

        return $card;
    }

    /**
     * Excelカラム情報を取得する
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'id',
            'character_id',
            'name',
            'rarity',
            'type',
            'add_date',
            'gasha_include',
            'limited',
            'created',
            'modified',
        ];
    }

    /**
     * カード情報を返す
     * @param Gasha $gasha ガシャ情報
     * @return array $cards
     */
    public function findGashaTargetCards(Gasha $gasha)
    {
        $start_date = $gasha->start_date->i18nFormat('yyyy-MM-dd');
        $query = $this->find();
        $rarity_cases = $query->newExpr()->addCase(
            [
                $query->newExpr()->add(['rarity' => '02']),
                $query->newExpr()->add(['rarity' => '03']),
                $query->newExpr()->add(['rarity' => '04'])
            ],
            ['R', 'SR', 'SSR'],
            ['string', 'string', 'string']
        );
        $type_cases = $query->newExpr()->addCase(
            [
                $query->newExpr()->add(['type' => '01']),
                $query->newExpr()->add(['type' => '02']),
                $query->newExpr()->add(['type' => '03'])
            ],
            ['Princess', 'Fairy', 'Angel'],
            ['string', 'string', 'string']
        );
        $query->select(['id', 'character_id', 'name', 'rarity' => $rarity_cases, 'type' => $type_cases])
        ->enableHydration(false);

        // 恒常カード情報を取得
        $cards = $query->where([
            'gasha_include' => true,
            'limited' => '01',
            'add_date <=' => $start_date
        ])->toArray();

        if ($gasha->isLimited()) {
            // 限定カードを取得
            $limited_cards = $query->where([
                'gasha_include' => true,
                'limited' => '02',
                'add_date' => $start_date
            ], [], true)->toArray();
            $cards = array_merge($limited_cards, $cards);
        } elseif ($gasha->isFesLimited()) {
            // フェス限定カードを取得
            $fes_limited_cards = $query->where([
                'gasha_include' => true,
                'limited' => '03',
                'add_date <=' => $start_date, // 過去のフェス限を含める
            ], [], true)->toArray();
            $cards = array_merge($fes_limited_cards, $cards);
        } elseif ($gasha->isReprintLimited()) {
            // 復刻？の条件追加
            $card_reprints = TableRegistry::getTableLocator()->get('CardReprints');
            $sub_query = $card_reprints->find()->select(['card_id'])->where(['gasha_id' => $gasha->id]);

            $reprint_limited_cards = $query->where([
                'Cards.id IN' => $sub_query,
                'Cards.gasha_include' => true,
                'Cards.limited' => '02',
            ], [], true)->toArray();
            $cards = array_merge($reprint_limited_cards, $cards);
        }

        // ピックアップ情報を取得して付加
        $card_ids = Hash::extract($cards, '{n}.id');
        $gasha_pickups = TableRegistry::getTableLocator()->get('GashaPickups');
        $pickup_targets = $gasha_pickups->find()->select(['card_id'])->where([
            'card_id IN' => $card_ids,
            'gasha_id' => $gasha->id,
        ])
        ->enableHydration(false)->toArray();
        $pickup_targets = Hash::extract($pickup_targets, '{n}.card_id');
        foreach ($cards as $card_index => $card) {
            $cards[$card_index]['pickup'] = (in_array($card['id'], $pickup_targets));
        }

        // レアリティごとに持ち替え
        $cards = Hash::combine($cards, '{n}.id', '{n}', '{n}.rarity');

        return $cards;
    }

    /**
     * 引数のIDのカードの情報を返す
     *
     * ガシャを引く処理のレスポンスにセットするなカード情報とする
     * 順番も引数のカードIDの配列順とする
     *
     * @param array $card_ids カードIDの配列
     * @return array
     */
    public function findByIds($card_ids = [])
    {

        $query = $this->find();
        $rarity_cases = $query->newExpr()->addCase(
            [
                        $query->newExpr()->add(['rarity' => '02']),
                        $query->newExpr()->add(['rarity' => '03']),
                        $query->newExpr()->add(['rarity' => '04'])
                ],
            ['R', 'SR', 'SSR'],
            ['string', 'string', 'string']
        );
        $type_cases = $query->newExpr()->addCase(
            [
                        $query->newExpr()->add(['type' => '01']),
                        $query->newExpr()->add(['type' => '02']),
                        $query->newExpr()->add(['type' => '03'])
                ],
            ['Princess', 'Fairy', 'Angel'],
            ['string', 'string', 'string']
        );
        $cards = $query->select(['id', 'name', 'rarity' => $rarity_cases, 'type' => $type_cases])
        ->where(['id IN' => $card_ids])
        ->enableHydration(false)->toArray();
        $cards = Hash::combine($cards, '{n}.id', '{n}');

        // 引数の並びを保持した結果を返す
        $results = [];
        foreach ($card_ids as $card_id) {
            $results[] = $cards[$card_id];
        }
        return $results;
    }
}
