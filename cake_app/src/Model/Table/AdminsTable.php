<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;

/**
 * Admins Model
 *
 * @method \App\Model\Entity\Admin get($primaryKey, $options = [])
 * @method \App\Model\Entity\Admin newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Admin[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Admin|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Admin[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Admin findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdminsTable extends AppTable
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

        $this->setTable('admins');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * auth finder Method.
     *
     * @param \Cake\ORM\Query $query query object
     * @param array $options option array
     * @return \Cake\ORM\Query
     */
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query->select(['id', 'mail', 'password', 'privilege']);

        return $query;
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
            ->scalar('mail')
            ->maxLength('mail', 255)
            ->requirePresence('mail', 'create')
            ->notEmptyString('mail');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->allowEmptyString('privilege');

        $validator
            ->scalar('delete_flag')
            ->maxLength('delete_flag', 1)
            ->notEmptyString('delete_flag');

        return $validator;
    }

    /**
     * patchEntityのオーバーライド
     * {@inheritDoc}
     * @see \Cake\ORM\Table::patchEntity()
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = [])
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = encrypt_password($data['password']);
        }
        return parent::patchEntity($entity, $data, $options);
    }

}
