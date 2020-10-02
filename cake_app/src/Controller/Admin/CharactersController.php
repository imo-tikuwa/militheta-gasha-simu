<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;

/**
 * Characters Controller
 *
 * @property \App\Model\Table\CharactersTable $Characters
 *
 * @method \App\Model\Entity\Character[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CharactersController extends AppController
{

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
        $characters = $this->paginate($query);

        $this->set(compact('characters'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Characters->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Characters->aliasField('id') => $request['id']]);
        }
        // 名前
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->Characters->aliasField('name') => $request['name']]);
        }

        return $query;
    }

    /**
     * View method
     *
     * @param string|null $id Character id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $character = $this->Characters->get($id);

        $this->set('character', $character);
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
     * @param string|null $id キャラクターID
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
     * @param string|null $id キャラクターID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->request->action == 'edit') {
            $character = $this->Characters->get($id);
        } else {
            $character = $this->Characters->newEntity();
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $character = $this->Characters->patchEntity($character, $this->request->getData(), ['associated' => ['Cards']]);
            if (!$character->hasErrors()) {
                $conn = $this->Characters->getConnection();
                $conn->begin();
                if ($this->Characters->save($character, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('キャラクターの登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Characters')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('character'));
        $this->render('edit');
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->request->getQueryParams();
        $characters = $this->_getQuery($request)->toArray();
        $_serialize = 'characters';
        $_header = $this->Characters->getCsvHeaders();
        $_extract = [
            // ID
            'id',
            // 名前
            'name',
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
        $this->response = $this->response->withDownload("characters-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('characters', '_serialize', '_header', '_extract', '_csvEncoding'));
    }
}
