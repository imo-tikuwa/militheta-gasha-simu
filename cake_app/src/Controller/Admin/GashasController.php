<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\DeleteType;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use Cake\Utility\Hash;

/**
 * Gashas Controller
 *
 * @property \App\Model\Table\GashasTable $Gashas
 *
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashasController extends AppController
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
        $gashas = $this->paginate($query);

        $this->set(compact('gashas'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request
     */
    private function _getQuery($request)
    {
        $query = $this->Gashas->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Gashas->aliasField('id') => $request['id']]);
        }
        // ガシャ開始日
        if (isset($request['start_date']) && !is_null($request['start_date']) && $request['start_date'] !== '') {
            $query->where([$this->Gashas->aliasField('start_date') => $request['start_date']]);
        }
        // ガシャ終了日
        if (isset($request['end_date']) && !is_null($request['end_date']) && $request['end_date'] !== '') {
            $query->where([$this->Gashas->aliasField('end_date') => $request['end_date']]);
        }
        // ガシャタイトル
        if (isset($request['title']) && !is_null($request['title']) && $request['title'] !== '') {
            $query->where([$this->Gashas->aliasField('title LIKE') => "%{$request['title']}%"]);
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
        $gasha = $this->Gashas->get($id);

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
            $gasha = $this->Gashas->get($id);
        } else {
            $gasha = $this->Gashas->newEntity();
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conn = $this->Gashas->getConnection();
            $conn->begin();
            $gasha = $this->Gashas->patchEntity($gasha, $this->request->getData(), ['associated' => ['CardReprints']]);
            if ($this->Gashas->save($gasha, ['atomic' => false])) {
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
        if ($this->Gashas->deleteRecord($id, DeleteType::LOGICAL)) {
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
        $gashas = $this->_getQuery($request)->toArray();
        $_serialize = 'gashas';
        $_header = $this->Gashas->getCsvHeaders();
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
        $this->response = $this->response->withDownload("gashas-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('gashas', '_serialize', '_header', '_extract', '_csvEncoding'));
    }

    /**
     * CSVインポート
     * @return \Cake\Http\Response|NULL
     */
    public function csvImport() {

        $csv_import_file = @$_FILES["csv_import_file"]["tmp_name"];
        if (is_uploaded_file($csv_import_file)){
            $conn = $this->Gashas->getConnection();
            try {
                if (($handle = fopen($csv_import_file, "r")) !== false) {
                    $conn->begin();
                    $index = 0;
                    $insert_count = 0;
                    $update_count = 0;
                    while ($csv_row = fgetcsv($handle)) {

                        // ヘッダチェック
                        if ($index == 0) {
                            if ($this->Gashas->getCsvHeaders() != $csv_row) {
                                throw new \Exception('HeaderCheckError');
                            }
                            $index++;
                            continue;
                        }
                        $index++;

                        // CSV1行の情報を変換
                        $csv_data = $this->Gashas->getCsvData($csv_row);

                        // 更新のとき既存データ取得、新規のとき空のエンティティを作成
                        if (!empty($csv_data['id'])) {
                            $gasha = $this->Gashas->get($csv_data['id']);
                            $update_count++;
                        } else {
                            $gasha = $this->Gashas->newEntity();
                            $insert_count++;
                        }

                        // CSVのデータで上書きして保存
                        $gasha = $this->Gashas->patchEntity($gasha, $csv_data);
                        if (!$this->Gashas->save($gasha, ['atomic' => false])) {
                            throw new \Exception('SaveError');
                        }
                    }
                    if (!$conn->commit()) {
                        throw new \Exception('CommitError');
                    }
                    $this->Flash->success("ガシャCSVの登録が完了しました。<br />新規：{$insert_count}件<br />更新：{$update_count}件", ['params' => ['escape' => false]]);
                }
            } catch (\Exception $e) {
                $error_message = 'ガシャCSVの登録でエラーが発生しました。';
                if (!empty($e->getMessage())) {
                    $error_message .= "(" . $e->getMessage() . ")";
                }
                $this->Flash->error($error_message);
                $conn->rollback();
            }
        }
        return $this->redirect(['action' => 'index']);
    }
}
