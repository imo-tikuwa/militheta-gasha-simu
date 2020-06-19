<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\DeleteType;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * GashaPickups Controller
 *
 * @property \App\Model\Table\GashaPickupsTable $GashaPickups
 *
 * @method \App\Model\Entity\GashaPickup[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashaPickupsController extends AppController
{

    /**
     * Initialize Method.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        if (!in_array($this->request->action, ['delete', 'csvExport'], true)) {
            // ガシャIDの選択肢
            $this->Gashas = TableRegistry::getTableLocator()->get('Gashas');
            $gashas = $this->Gashas->find()->select(['id', 'title', 'start_date'])->enableHydration(false)->order(['id' => 'DESC'])->toArray();
            if (!empty($gashas)) {
                $gashas = Hash::combine($gashas, '{n}.id', ['%s　%s', '{n}.start_date', '{n}.title']);
                $gashas = ["" => "　"] + $gashas;
            }
            $this->set('gashas', $gashas);
            // カードIDの選択肢
            $this->set('cards', $this->GashaPickups->findForeignSelectionData('Cards', 'name', true));
        }
    }

    /**
     * Paging setting.
     */
    public $paginate = [
        'limit' => 20,
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $request = $this->request->getQueryParams();
        $this->set('params', $request);
        $query = $this->_getQuery($request);
        $gasha_pickups = $this->paginate($query);

        $this->set(compact('gasha_pickups'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->GashaPickups->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->GashaPickups->aliasField('id') => $request['id']]);
        }
        // ガシャID
        if (isset($request['gasha_id']) && !is_null($request['gasha_id']) && $request['gasha_id'] !== '') {
            $query->where(['Gashas.id' => $request['gasha_id']]);
        }
        // カードID
        if (isset($request['card_id']) && !is_null($request['card_id']) && $request['card_id'] !== '') {
            $query->where(['Cards.id' => $request['card_id']]);
        }
        $query->group('GashaPickups.id');

        return $query->contain(['Gashas', 'Cards']);
    }

    /**
     * View method
     *
     * @param string|null $id Gasha Pickup id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gashaPickup = $this->GashaPickups->get($id, ['contain' => ['Gashas', 'Cards']]);

        $this->set('gashaPickup', $gashaPickup);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        return $this->_form();
    }

    /**
     * Edit method
     *
     * @param string|null $id ピックアップ情報ID
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        return $this->_form($id);
    }

    /**
     * Add and Edit Common method
     *
     * @param string|null $id ピックアップ情報ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->request->action == 'edit') {
            $gasha_pickup = $this->GashaPickups->get($id);
        } else {
            $gasha_pickup = $this->GashaPickups->newEntity();
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conn = $this->GashaPickups->getConnection();
            $conn->begin();
            $gasha_pickup = $this->GashaPickups->patchEntity($gasha_pickup, $this->request->getData(), ['associated' => ['Gashas', 'Cards']]);
            if ($this->GashaPickups->save($gasha_pickup, ['atomic' => false])) {
                $conn->commit();
                $this->Flash->success('ピックアップ情報の登録が完了しました。');

                return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.GashaPickups')]);
            }
            $conn->rollback();
            $this->Flash->error('エラーが発生しました。');
        }
        $this->set(compact('gasha_pickup'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id ピックアップ情報ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        if ($this->GashaPickups->deleteRecord($id, DeleteType::LOGICAL)) {
            $this->Flash->success('ピックアップ情報の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.GashaPickups')]);
    }

    /**
     * CSVエクスポート
     * @return void
     */
    public function csvExport()
    {
        $request = $this->request->getQueryParams();
        $gasha_pickups = $this->_getQuery($request)->toArray();
        $_serialize = 'gasha_pickups';
        $_header = $this->GashaPickups->getCsvHeaders();
        $_extract = [
            // ID
            'id',
            // ガシャID
            function ($row) {
                return @$row['gasha']['title'];
            },
            // カードID
            function ($row) {
                return @$row['card']['name'];
            },
            // 作成日時
            function ($row) {
                if ($row['created'] instanceof FrozenTime) {
                    return @$row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
                }

                return "";
            },
            // 更新日時
            function ($row) {
                if ($row['modified'] instanceof FrozenTime) {
                    return @$row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
                }

                return "";
            },
        ];

        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('Asia/Tokyo'));

        $_csvEncoding = 'UTF-8';
        $this->response = $this->response->withDownload("gasha_pickups-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('gasha_pickups', '_serialize', '_header', '_extract', '_csvEncoding'));
    }
}
