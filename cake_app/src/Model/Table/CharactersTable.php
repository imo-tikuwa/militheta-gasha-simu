<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Characters Model
 *
 * @property \App\Model\Table\CardsTable&\Cake\ORM\Association\HasMany $Cards
 *
 * @method \App\Model\Entity\Character get($primaryKey, $options = [])
 * @method \App\Model\Entity\Character newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Character[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Character|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Character saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Character patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Character[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Character findOrCreate($search, callable $callback = null, $options = [])
 *
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
    public function initialize(array $config)
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        return $validator;
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
            '名前',
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
            'name',
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

        unset($csv_data['created']);
        unset($csv_data['modified']);

        return $csv_data;
    }
}
