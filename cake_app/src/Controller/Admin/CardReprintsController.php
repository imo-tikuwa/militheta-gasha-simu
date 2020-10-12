<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * CardReprints Controller
 *
 * @property \App\Model\Table\CardReprintsTable $CardReprints
 *
 * @method \App\Model\Entity\CardReprint[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CardReprintsController extends AppController
{

    /**
     * Initialize Method.
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        if (!in_array($this->getRequest()->getParam('action'), ['delete', 'csvExport'], true)) {
            // ガシャIDの選択肢
            $this->Gashas = TableRegistry::getTableLocator()->get('Gashas');
            $gashas = $this->Gashas->find()->select(['id', 'title', 'start_date'])->where(['title LIKE' => '【限定復刻】%'])->enableHydration(false)->order(['id' => 'DESC'])->toArray();
            if (!empty($gashas)) {
                $gashas = Hash::combine($gashas, '{n}.id', ['%s　%s', '{n}.start_date', '{n}.title']);
                $gashas = ["" => "　"] + $gashas;
            }
            $this->set('gashas', $gashas);
            // カードIDの選択肢
            $this->set('cards', $this->CardReprints->findForeignSelectionData('Cards', 'name', true));
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
        $request = $this->getRequest()->getQueryParams();
        $this->set('params', $request);
        $query = $this->_getQuery($request);
        $card_reprints = $this->paginate($query);

        $this->set(compact('card_reprints'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->CardReprints->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->CardReprints->aliasField('id') => $request['id']]);
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
                $search_snippet_conditions[] = [$this->CardReprints->aliasField('search_snippet LIKE') => "%{$search_snippet}%"];
            }
            if (isset($request['search_snippet_format']) && $request['search_snippet_format'] == 'AND') {
                $query->where($search_snippet_conditions);
            } else {
                $query->where(function($exp) use ($search_snippet_conditions) {
                    return $exp->or($search_snippet_conditions);
                });
            }
        }
        $query->group('CardReprints.id');

        return $query->contain(['Gashas', 'Cards']);
    }

    /**
     * View method
     *
     * @param string|null $id Card Reprint id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cardReprint = $this->CardReprints->get($id, ['contain' => ['Gashas', 'Cards']]);

        $this->set('cardReprint', $cardReprint);
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
     * @param string|null $id 復刻情報ID
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
     * @param string|null $id 復刻情報ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $card_reprint = $this->CardReprints->get($id);
        } else {
            $card_reprint = $this->CardReprints->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $card_reprint = $this->CardReprints->patchEntity($card_reprint, $this->getRequest()->getData(), ['associated' => ['Gashas', 'Cards']]);
            if (!$card_reprint->hasErrors()) {
                $conn = $this->CardReprints->getConnection();
                $conn->begin();
                if ($this->CardReprints->save($card_reprint, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('復刻情報の登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.CardReprints')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('card_reprint'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id 復刻情報ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $entity = $this->CardReprints->get($id);
        if ($this->CardReprints->delete($entity)) {
            $this->Flash->success('復刻情報の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.CardReprints')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $card_reprints = $this->_getQuery($request)->toArray();
        $_serialize = 'card_reprints';
        $_header = $this->CardReprints->getCsvHeaders();
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
        $this->response = $this->response->withDownload("card_reprints-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('card_reprints', '_serialize', '_header', '_extract', '_csvEncoding'));
    }
}
