<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Model\Entity\Gasha;

/**
 * Cards Model
 *
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 * @property \App\Model\Table\CardReprintsTable|\Cake\ORM\Association\HasMany $CardReprints
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
        $this->hasMany('CardReprints', [
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

        $validator
            ->allowEmpty('gasha_include');

        $validator
            ->allowEmpty('limited');

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
            'ガシャ対象？',
            '限定？',
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
            'gasha_include',
            'limited',
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

	/**
	 * カード情報を返す
	 * @param Gasha $gasha
	 */
	public function findGashaTargetCards(Gasha $gasha) {

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

		// 限定カードを取得
		if ($gasha->isLimited()) {

			$limited_cards = $query->where([
					'gasha_include' => 1,
					'limited' => 1,
					'add_date' => $start_date
			], [], true)->toArray();
			$cards = array_merge($limited_cards, $cards);

		}
		// フェス限定カードを取得
		else if ($gasha->isFesLimited()) {

			$fes_limited_cards = $query->where([
					'gasha_include' => 1,
					'limited' => 2,
					'add_date <=' => $start_date, // 過去のフェス限を含める
			], [], true)->toArray();
			$cards = array_merge($fes_limited_cards, $cards);

		}
		// 復刻？の条件追加
		else if ($gasha->isReprintLimited()) {

			$card_reprints = TableRegistry::getTableLocator()->get('CardReprints');
			$sub_query = $card_reprints->find()->select(['card_id'])->where(['gasha_id' => $gasha->id]);

			$reprint_limited_cards = $query->where([
					'Cards.id IN' => $sub_query,
					'Cards.gasha_include' => 1,
					'Cards.limited' => 1,
			], [], true)->toArray();
			$cards = array_merge($reprint_limited_cards, $cards);
		}

		// TODO 以下の処理は上のカード情報を取得するORMにまとめられそう
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
	 * @param array $card_ids
	 */
	public function findByIds($card_ids = []) {

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
