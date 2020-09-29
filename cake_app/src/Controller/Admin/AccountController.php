<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Utils\AuthUtils;
use Cake\Event\Event;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;

/**
 * Account Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccountController extends AppController
{

    /**
     * Paging setting.
     */
    public $paginate = [
        'limit' => 20,
    ];

    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\Admin\AppController::beforeFilter()
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadModel('Admins');

        // スーパーユーザー以外はアクセス不可
        if (!AuthUtils::isSuperUser($this->request)) {
            $this->Flash->error(MESSAGE_AUTH_ERROR);

            return $this->redirect(['controller' => 'top', 'action' => 'index']);
        }
    }

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
        $accounts = $this->paginate($query);

        $this->set(compact('accounts'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Admins->find();
        $query->where([$this->Admins->aliasField('id <>') => SUPER_USER_ID]);
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Admins->aliasField('id') => $request['id']]);
        }
        // メールアドレス
        if (isset($request['mail']) && !is_null($request['mail']) && $request['mail'] !== '') {
            $query->where([$this->Admins->aliasField('mail LIKE') => "%{$request['mail']}%"]);
        }

        return $query;
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
     * @param string|null $id アカウントID
     * @return \Cake\Http\Response|null
     */
    public function edit($id = null)
    {
        return $this->_form($id);
    }

    /**
     * Add and Edit Common method
     *
     * @param string|null $id アカウントID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if (SUPER_USER_ID == $id) {
            $this->Flash->error('エラーが発生しました。');

            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->action == 'edit') {
            $admin = $this->Admins->get($id);
        } else {
            $admin = $this->Admins->newEntity();
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conn = $this->Admins->getConnection();
            $conn->begin();
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());
            if ($this->Admins->save($admin, ['atomic' => false])) {
                $conn->commit();
                $this->Flash->success('アカウントの登録が完了しました。');

                return $this->redirect(['action' => 'index']);
            }
            $conn->rollback();
            $this->Flash->error('エラーが発生しました。');
        }
        $this->set(compact('admin'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id 管理者ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (SUPER_USER_ID == $id) {
            $this->Flash->error('エラーが発生しました。');

            return $this->redirect(['action' => 'index']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->Admins->get($id);
        if ($this->Admins->delete($entity)) {
            $this->Flash->success('管理者の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index']);
    }
}
