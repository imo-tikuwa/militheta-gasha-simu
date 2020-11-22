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
 * Gashas Model
 *
 * @property \App\Model\Table\CardReprintsTable&\Cake\ORM\Association\HasMany $CardReprints
 * @property \App\Model\Table\GashaPickupsTable&\Cake\ORM\Association\HasMany $GashaPickups
 *
 * @method \App\Model\Entity\Gasha newEmptyEntity()
 * @method \App\Model\Entity\Gasha newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Gasha[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Gasha get($primaryKey, $options = [])
 * @method \App\Model\Entity\Gasha findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Gasha patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Gasha[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Gasha|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gasha saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GashasTable extends AppTable
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

        $this->setTable('gashas');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
        $this->hasMany('CardReprints', [
            'foreignKey' => 'gasha_id',
        ]);
        $this->hasMany('GashaPickups', [
            'foreignKey' => 'gasha_id',
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

        // ガシャ開始日
        $validator
            ->requirePresence('start_date', true, 'ガシャ開始日を入力してください。')
            ->add('start_date', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => 'ガシャ開始日を正しく入力してください。',
                'last' => true
            ])
            ->notEmptyDate('start_date', 'ガシャ開始日を入力してください。');

        // ガシャ終了日
        $validator
            ->requirePresence('end_date', true, 'ガシャ終了日を入力してください。')
            ->add('end_date', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => 'ガシャ終了日を正しく入力してください。',
                'last' => true
            ])
            ->notEmptyDate('end_date', 'ガシャ終了日を入力してください。');

        // ガシャタイトル
        $validator
            ->requirePresence('title', true, 'ガシャタイトルを入力してください。')
            ->add('title', 'scalar', [
                'rule' => 'isScalar',
                'message' => 'ガシャタイトルを正しく入力してください。',
                'last' => true
            ])
            ->add('title', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => 'ガシャタイトルは255文字以内で入力してください。',
                'last' => true
            ])
            ->notEmptyString('title', 'ガシャタイトルを入力してください。');

        // SSRレート
        $validator
            ->requirePresence('ssr_rate', true, 'SSRレートを入力してください。')
            ->add('ssr_rate', 'integer', [
                'rule' => 'isInteger',
                'message' => 'SSRレートを正しく入力してください。',
                'last' => true
            ])
            ->add('ssr_rate', 'greaterThanOrEqual', [
                'rule' => ['comparison', '>=', 0],
                'message' => 'SSRレートは0以上の値で入力してください。',
                'last' => true
            ])
            ->add('ssr_rate', 'lessThanOrEqual', [
                'rule' => ['comparison', '<=', 100],
                'message' => 'SSRレートは100以下の値で入力してください。',
                'last' => true
            ])
            ->notEmptyString('ssr_rate', 'SSRレートを入力してください。');

        // SRレート
        $validator
            ->requirePresence('sr_rate', true, 'SRレートを入力してください。')
            ->add('sr_rate', 'integer', [
                'rule' => 'isInteger',
                'message' => 'SRレートを正しく入力してください。',
                'last' => true
            ])
            ->add('sr_rate', 'greaterThanOrEqual', [
                'rule' => ['comparison', '>=', 0],
                'message' => 'SRレートは0以上の値で入力してください。',
                'last' => true
            ])
            ->add('sr_rate', 'lessThanOrEqual', [
                'rule' => ['comparison', '<=', 100],
                'message' => 'SRレートは100以下の値で入力してください。',
                'last' => true
            ])
            ->notEmptyString('sr_rate', 'SRレートを入力してください。');

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
        $search_snippet[] = $data['title'];
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
            'ガシャ開始日',
            'ガシャ終了日',
            'ガシャタイトル',
            'SSRレート',
            'SRレート',
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
            'start_date',
            'end_date',
            'title',
            'ssr_rate',
            'sr_rate',
            'created',
            'modified',
        ];
    }

    /**
     * CSVの入力情報を元にエンティティを作成する
     * @param array $csv_row CSVの1行辺りの配列データ
     * @return \App\Model\Entity\Gasha エンティティ
     */
    public function createEntityByCsvRow($csv_row)
    {
        $csv_data = array_combine($this->getCsvColumns(), $csv_row);

        // SSRレート
        $csv_data['ssr_rate'] = preg_replace('/[^0-9]/', '', $csv_data['ssr_rate']);
        // SRレート
        $csv_data['sr_rate'] = preg_replace('/[^0-9]/', '', $csv_data['sr_rate']);

        unset($csv_data['created']);
        unset($csv_data['modified']);

        // Csvの入力情報を元にエンティティを作成
        if (!empty($csv_data['id'])) {
            $gasha = $this->get($csv_data['id']);
            $this->touch($gasha);
        } else {
            $gasha = $this->newEmptyEntity();
        }
        $gasha = $this->patchEntity($gasha, $csv_data, ['validate' => 'csv']);

        return $gasha;
    }

    /**
     * Excelカラム情報を取得する
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'id',
            'start_date',
            'end_date',
            'title',
            'ssr_rate',
            'sr_rate',
            'created',
            'modified',
        ];
    }

    /**
     * ガシャ情報を取得
     * @return array ガシャ情報
     */
    public function findGashaData()
    {
        $query = $this->find();
        $gasha_data = $query->select([
            'id',
            'start_date',
            'end_date',
            'title',
            'ssr_rate',
            'sr_rate'
        ])->enableHydration(false)
        ->order(['id' => 'DESC'])
        ->toArray();
        return $gasha_data;
    }

    /**
     * ガシャ情報をjson形式で取得する
     * @param array $gasha_datas ガシャ情報
     * @return string Key: ガシャID、Value:ガシャ情報なjsonテキスト
     */
    public function getGashaJsonData($gasha_datas = null)
    {
        if (is_null($gasha_datas)) {
            return null;
        }
        $json_data = [];
        foreach ($gasha_datas as $gasha_data) {
            $gasha_data['start_date'] = $gasha_data['start_date']->format('Y-m-d');
            $gasha_data['end_date'] = $gasha_data['end_date']->format('Y-m-d');
            $json_data[$gasha_data['id']] = $gasha_data;
        }
        return json_encode($json_data, JSON_UNESCAPED_UNICODE);
    }
}
