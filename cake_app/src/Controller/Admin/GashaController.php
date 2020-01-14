<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\DeleteType;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use Cake\Utility\Hash;

/**
 * Gasha Controller
 *
 * @property \App\Model\Table\GashaTable $Gasha
 *
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashaController extends AppController
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
        $gasha = $this->paginate($query);

        $this->set(compact('gasha'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request
     */
    private function _getQuery($request)
    {
        $query = $this->Gasha->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Gasha->aliasField('id') => $request['id']]);
        }
        // ガシャ開始日
        if (isset($request['start_date']) && !is_null($request['start_date']) && $request['start_date'] !== '') {
            $query->where([$this->Gasha->aliasField('start_date') => $request['start_date']]);
        }
        // ガシャ終了日
        if (isset($request['end_date']) && !is_null($request['end_date']) && $request['end_date'] !== '') {
            $query->where([$this->Gasha->aliasField('end_date') => $request['end_date']]);
        }
        // ガシャタイトル
        if (isset($request['title']) && !is_null($request['title']) && $request['title'] !== '') {
            $query->where([$this->Gasha->aliasField('title LIKE') => "%{$request['title']}%"]);
        }
        return $query;
    }

    /**
     * View method
     *
     * @param string|null $id Gasha id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gasha = $this->Gasha->get($id);

        $this->set('gasha', $gasha);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->_form();
    }

    /**
     * Edit method
     *
     * @param string|null $id ガシャID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->_form($id);
    }

    /**
     * Add and Edit Common method
     *
     * @param string|null $id ガシャID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->request->action == 'edit') {
            $gasha = $this->Gasha->get($id);
        } else {
            $gasha = $this->Gasha->newEntity();
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conn = $this->Gasha->getConnection();
            $conn->begin();
            $gasha = $this->Gasha->patchEntity($gasha, $this->request->getData(), ['associated' => []]);
            if ($this->Gasha->save($gasha, ['atomic' => false])) {
                $conn->commit();
                $this->Flash->success('ガシャの登録が完了しました。');
                return $this->redirect(['action' => 'index']);
            }
            $conn->rollback();
            $this->Flash->error('エラーが発生しました。');
        }
        $this->set(compact('gasha'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id ガシャID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Gasha->deleteRecord($id, DeleteType::LOGICAL)) {
            $this->Flash->success('ガシャの削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * CSVエクスポート
     */
    public function csvExport()
    {
        $request = $this->request->getQueryParams();
        $gasha = $this->_getQuery($request)->toArray();
        $_serialize = 'gasha';
        $_header = $this->Gasha->getCsvHeaders();
        $_extract = [
            // ID
            'id',
            // ガシャ開始日
            function ($row) {
                if ($row['start_date'] instanceof FrozenDate) {
                    return @$row['start_date']->i18nFormat('yyyy-MM-dd');
                }
                return "";
            },
            // ガシャ終了日
            function ($row) {
                if ($row['end_date'] instanceof FrozenDate) {
                    return @$row['end_date']->i18nFormat('yyyy-MM-dd');
                }
                return "";
            },
            // ガシャタイトル
            'title',
            // SSRレート
            function ($row) {
                if (!empty($row['ssr_rate'])) {
                    return $row['ssr_rate']."%";
                }
                return "";
            },
            // SRレート
            function ($row) {
                if (!empty($row['sr_rate'])) {
                    return $row['sr_rate']."%";
                }
                return "";
            },
            // SSRピックアップレート
            function ($row) {
                if (!empty($row['ssr_pickup_rate'])) {
                    return $row['ssr_pickup_rate']."%";
                }
                return "";
            },
            // SRピックアップレート
            function ($row) {
                if (!empty($row['sr_pickup_rate'])) {
                    return $row['sr_pickup_rate']."%";
                }
                return "";
            },
            // Rピックアップレート
            function ($row) {
                if (!empty($row['r_pickup_rate'])) {
                    return $row['r_pickup_rate']."%";
                }
                return "";
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
        $this->response = $this->response->withDownload("gasha-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('gasha', '_serialize', '_header', '_extract', '_csvEncoding'));
    }
}
