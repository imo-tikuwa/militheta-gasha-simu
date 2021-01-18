<?php
namespace App\Controller\Admin;

use App\Utils\AuthUtils;
use Cake\Event\EventInterface;
use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;

/**
 * Admin AppController
 *
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class AppController extends \App\Controller\AppController
{
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AppController::initialize()
     */
    public function initialize(): void
    {
        parent::initialize();

        /** load \Authentication\Controller\Component\AuthenticationComponent */
        $this->loadComponent('Authentication.Authentication');

        $this->viewBuilder()->setLayout('default_admin');
    }

    /**
     *
     * {@inheritDoc}
     * @see \Cake\Controller\Controller::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ログインチェック
        if (!$this->Authentication->getResult()->isValid()) {
            return;
        }

        // 権限チェック
        if (!$this->authorize($this->getRequest())) {
            $this->Flash->error(MESSAGE_AUTH_ERROR);

            return $this->redirect(['controller' => 'top', 'action' => 'index']);
        }

        // サイドメニューの展開状態をCookie管理する
        $sidemenu_toggle_class = $this->getRequest()->getCookie('sidemenu-toggle-class');
        if (empty($sidemenu_toggle_class)) {
            $sidemenu_toggle_class = 'sidebar-collapse';
            $this->response = $this->response->withCookie(Cookie::create('sidemenu-toggle-class', $sidemenu_toggle_class));
        }
        $this->set(compact('sidemenu_toggle_class'));
    }

    /**
     * cakephp/authorizationプラグインの代わりの認可処理
     *
     * @param ServerRequest $request リクエスト情報
     * @return bool
     */
    private function authorize(ServerRequest $request): bool
    {
        // 権限なしでアクセス可能なコントローラ
        if (in_array($request->getParam('controller'), ['Auth', 'Top'])) {
            return true;
        }

        // ログイン中のユーザーがアクセス中のアクションの権限を持っているかチェック
        return AuthUtils::hasRole($request);
    }
}
