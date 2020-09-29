<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;

/**
 * Cards Controller
 *
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CardsController extends AppController
{

    /**
     * Initialize Method.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        if (!in_array($this->request->action, ['delete', 'csvExport', 'csvImport'], true)) {
            // キャラクターの選択肢
            $this->set('characters', $this->Cards->findForeignSelectionData('Characters', 'name', true));
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
        $cards = $this->paginate($query);

        $this->set(compact('cards'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Cards->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Cards->aliasField('id') => $request['id']]);
        }
        // キャラクター
        if (isset($request['character_id']) && !is_null($request['character_id']) && $request['character_id'] !== '') {
            $query->where(['Characters.id' => $request['character_id']]);
        }
        // カード名
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->Cards->aliasField('name LIKE') => "%{$request['name']}%"]);
        }
        // レアリティ
        if (isset($request['rarity']) && !is_null($request['rarity']) && $request['rarity'] !== '') {
            $query->where([$this->Cards->aliasField('rarity') => $request['rarity']]);
        }
        // タイプ
        if (isset($request['type']) && !is_null($request['type']) && $request['type'] !== '') {
            $query->where([$this->Cards->aliasField('type') => $request['type']]);
        }
        // 実装日
        if (isset($request['add_date']) && !is_null($request['add_date']) && $request['add_date'] !== '') {
            $query->where([$this->Cards->aliasField('add_date') => $request['add_date']]);
        }
        // ガシャ対象？
        if (isset($request['gasha_include']) && !is_null($request['gasha_include']) && $request['gasha_include'] !== '') {
            $query->where([$this->Cards->aliasField('gasha_include') => $request['gasha_include']]);
        }
        // 限定？
        if (isset($request['limited']) && !is_null($request['limited']) && $request['limited'] !== '') {
            $query->where([$this->Cards->aliasField('limited') => $request['limited']]);
        }
        // フリーワード
        if (isset($request['search_snippet']) && !is_null($request['search_snippet']) && $request['search_snippet'] !== '') {
            $search_snippet_conditions = [];
            foreach (explode(' ', str_replace('　', ' ', $request['search_snippet'])) as $search_snippet) {
                $search_snippet_conditions[] = [$this->Cards->aliasField('search_snippet LIKE') => "%{$search_snippet}%"];
            }
            if (isset($request['search_snippet_format']) && $request['search_snippet_format'] == 'AND') {
                $query->where($search_snippet_conditions);
            } else {
                $query->where(function($exp) use ($search_snippet_conditions) {
                    return $exp->or($search_snippet_conditions);
                });
            }
        }
        $query->group('Cards.id');

        return $query->contain(['Characters', 'CardReprints']);
    }

    /**
     * View method
     *
     * @param string|null $id Card id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $card = $this->Cards->get($id, ['contain' => ['Characters', 'CardReprints']]);

        $this->set('card', $card);
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
     * @param string|null $id カードID
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
     * @param string|null $id カードID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->request->action == 'edit') {
            $card = $this->Cards->get($id);
        } else {
            $card = $this->Cards->newEntity();
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $card = $this->Cards->patchEntity($card, $this->request->getData(), ['associated' => ['Characters', 'CardReprints']]);
            if (!$card->hasErrors()) {
                $conn = $this->Cards->getConnection();
                $conn->begin();
                if ($this->Cards->save($card, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('カードの登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Cards')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('card'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id カードID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->Cards->get($id);
        if ($this->Cards->delete($entity)) {
            $this->Flash->success('カードの削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Cards')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->request->getQueryParams();
        $cards = $this->_getQuery($request)->toArray();
        $_serialize = 'cards';
        $_header = $this->Cards->getCsvHeaders();
        $_extract = [
            // ID
            'id',
            // キャラクター
            function ($row) {
                return @$row['character']['name'];
            },
            // カード名
            'name',
            // レアリティ
            function ($row) {
                if (!empty($row['rarity'])) {
                    return _code('Codes.Cards.rarity.' . $row['rarity']);
                }

                return "";
            },
            // タイプ
            function ($row) {
                if (!empty($row['type'])) {
                    return _code('Codes.Cards.type.' . $row['type']);
                }

                return "";
            },
            // 実装日
            function ($row) {
                if ($row['add_date'] instanceof FrozenDate) {
                    return @$row['add_date']->i18nFormat('yyyy-MM-dd');
                }

                return "";
            },
            // ガシャ対象？
            'gasha_include',
            // 限定？
            'limited',
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
        $this->response = $this->response->withDownload("cards-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('cards', '_serialize', '_header', '_extract', '_csvEncoding'));
    }

    /**
     * csv import method
     * @return \Cake\Http\Response|NULL
     */
    public function csvImport()
    {
        $csv_import_file = @$_FILES["csv_import_file"]["tmp_name"];
        if (is_uploaded_file($csv_import_file)) {
            $conn = $this->Cards->getConnection();
            try {
                if (($handle = fopen($csv_import_file, "r")) !== false) {
                    $conn->begin();
                    $index = 0;
                    $insert_count = 0;
                    $update_count = 0;
                    while ($csv_row = fgetcsv($handle)) {
                        // ヘッダチェック
                        if ($index == 0) {
                            if ($this->Cards->getCsvHeaders() != $csv_row) {
                                throw new \Exception('HeaderCheckError');
                            }
                            $index++;
                            continue;
                        }
                        $index++;

                        // CSV1行の情報を変換
                        $csv_data = $this->Cards->getCsvData($csv_row);

                        // 更新のとき既存データ取得、新規のとき空のエンティティを作成
                        if (!empty($csv_data['id'])) {
                            $card = $this->Cards->get($csv_data['id']);
                            $update_count++;
                        } else {
                            $card = $this->Cards->newEntity();
                            $insert_count++;
                        }

                        // CSVのデータで上書きして保存
                        $card = $this->Cards->patchEntity($card, $csv_data);
                        if (!$this->Cards->save($card, ['atomic' => false])) {
                            throw new \Exception('SaveError');
                        }
                    }
                    if (!$conn->commit()) {
                        throw new \Exception('CommitError');
                    }
                    $this->Flash->success("カードCSVの登録が完了しました。<br />新規：{$insert_count}件<br />更新：{$update_count}件", ['params' => ['escape' => false]]);
                }
            } catch (\Exception $e) {
                $error_message = 'カードCSVの登録でエラーが発生しました。';
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
