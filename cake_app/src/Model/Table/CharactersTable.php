<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Validation\Validator;

/**
 * Characters Model
 *
 * @property \App\Model\Table\CardsTable&\Cake\ORM\Association\HasMany $Cards
 * @method \App\Model\Entity\Character newEmptyEntity()
 * @method \App\Model\Entity\Character newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Character[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Character get($primaryKey, $options = [])
 * @method \App\Model\Entity\Character findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Character patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Character[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Character|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Character saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Character[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Character[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Character[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Character[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CharactersTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('characters');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->hasMany('Cards', [
            'foreignKey' => 'character_id',
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

        // 名前
        $validator
            ->requirePresence('name', true, '名前を入力してください。')
            ->add('name', 'scalar', [
                'rule' => 'isScalar',
                'message' => '名前を正しく入力してください。',
                'last' => true,
            ])
            ->add('name', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => '名前は255文字以内で入力してください。',
                'last' => true,
            ])
            ->notEmptyString('name', '名前を入力してください。');

        return $validator;
    }

    /**
     * patchEntityのオーバーライド
     * ファイル項目、GoogleMap項目のJSON文字列を配列に変換する
     *
     * @see \Cake\ORM\Table::patchEntity()
     * @param \Cake\Datasource\EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     * @return \App\Model\Entity\Character
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        $entity = parent::patchEntity($entity, $data, $options);
        assert($entity instanceof \App\Model\Entity\Character);

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
        // 名前
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->aliasField('name') => $request['name']]);
        }

        return $query;
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
            '名前',
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
            'name',
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
            'name',
            'created',
            'modified',
        ];
    }
}
