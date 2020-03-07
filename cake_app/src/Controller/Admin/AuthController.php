<?php
namespace App\Controller\Admin;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Auth Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 */
class AuthController extends AppController
{
    /**
     *
     * {@inheritDoc}
     * @see \Cake\Controller\Controller::beforeFilter()
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['logout']);

        // ログイン画面はレイアウトを使用しない
        $this->viewBuilder()->setLayout(false);
    }

    /**
     * ログイン
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                // 他のユーザーセッションを消す
                $this->request->getSession()->destroy();

                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('ログインIDかパスワードが正しくありません。');
        }
    }

    /**
     * ログアウト
     * @return \Cake\Http\Response|NULL
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
