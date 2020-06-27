<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Model\Entity\Gasha;

/**
 * Cards Model
 *
 * @property \App\Model\Table\CharactersTable&\Cake\ORM\Association\BelongsTo $Characters
 * @property \App\Model\Table\CardReprintsTable&\Cake\ORM\Association\HasMany $CardReprints
 * @property \App\Model\Table\GashaPickupsTable&\Cake\ORM\Association\HasMany $GashaPickups
 *
 * @method \App\Model\Entity\Card get($primaryKey, $options = [])
 * @method \App\Model\Entity\Card newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Card[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Card|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Card saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
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
    public function validationDefault(Validator $validator)
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        // キャラクター
        $validator
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
            ->add('add_date', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => '実装日を正しく入力してください。',
                'last' => true
            ])
            ->notEmptyDate('add_date', '実装日を入力してください。');

        // ガシャ対象？
        $validator
            ->add('gasha_include', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Cards.gasha_include'));
                },
                'message' => 'ガシャ対象？に不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('gasha_include', 'ガシャ対象？を入力してください。');

        // 限定？
        $validator
            ->add('limited', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Cards.limited'));
                },
                'message' => '限定？に不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('limited', '限定？を入力してください。');

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
     * @param EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = [])
    {
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
     * CSVの入力情報を取得する
     * @param array $csv_row CSVの1行辺りの配列データ
     * @return array データ登録用に変換した配列データ
     */
    public function getCsvData($csv_row)
    {
        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // キャラクター
        $characters = TableRegistry::getTableLocator()->get('Characters');
        $character_data = $characters->find()->select(['id'])->where(['name' => $csv_data['character_id']])->first();
        if (!empty($character_data)) {
            $csv_data['character_id'] = (string)$character_data->id;
        } else {
            $csv_data['character_id'] = null;
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
        unset($csv_data['created']);
        unset($csv_data['modified']);

        return $csv_data;
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
            'gasha_include' => 1,
            'limited' => 0,
            'add_date <=' => $start_date
        ])->toArray();

        if ($gasha->isLimited()) {
            // 限定カードを取得
            $limited_cards = $query->where([
                'gasha_include' => 1,
                'limited' => 1,
                'add_date' => $start_date
            ], [], true)->toArray();
            $cards = array_merge($limited_cards, $cards);
        } elseif ($gasha->isFesLimited()) {
            // フェス限定カードを取得
            $fes_limited_cards = $query->where([
                'gasha_include' => 1,
                'limited' => 2,
                'add_date <=' => $start_date, // 過去のフェス限を含める
            ], [], true)->toArray();
            $cards = array_merge($fes_limited_cards, $cards);
        } elseif ($gasha->isReprintLimited()) {
            // 復刻？の条件追加
            $card_reprints = TableRegistry::getTableLocator()->get('CardReprints');
            $sub_query = $card_reprints->find()->select(['card_id'])->where(['gasha_id' => $gasha->id]);

            $reprint_limited_cards = $query->where([
                'Cards.id IN' => $sub_query,
                'Cards.gasha_include' => 1,
                'Cards.limited' => 1,
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
