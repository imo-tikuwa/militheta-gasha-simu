<?php
namespace App\Controller\Admin;

use Cake\Event\Event;

class AppController extends \App\Controller\AppController
{
	/**
	 *
	 * {@inheritDoc}
	 * @see \Cake\Controller\Controller::beforeFilter()
	 */
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);

		// サイドメニューの展開状態をCookie管理する
		$sidemenu_expand_css_class = $this->request->getCookie('sidemenu-toggle-class');
		if (empty($sidemenu_expand_css_class)) {
			$sidemenu_expand_css_class = 'sidebar-collapse';
			$this->response = $this->response->withCookie('sidemenu-toggle-class', $sidemenu_expand_css_class);
		}
		$this->set(compact('sidemenu_toggle_class'));
	}

	public function initialize()
	{
		parent::initialize();

		$this->viewBuilder()->setLayout('default_admin');

		$this->loadComponent('Auth', [
				// 認証設定
				'authenticate' => [
						'Form' => [
								'userModel' => 'Admins',
								'fields' => [
										'username' => 'mail',
										'password' => 'password'
								],
								'finder' => 'auth',
								'passwordHasher' => [
										'className' => 'Ex' // ExPasswordHasherを参照
								],
						],
				],
				// ログイン画面
				'loginAction' => [
						'controller' => 'Auth',
						'action' => 'login',
						'prefix' => 'admin',
				],
				// ログイン後のリダイレクト先
				'loginRedirect' => [
						'controller' => 'Top',
						'action' => 'index',
						'prefix' => 'admin',
				],
				// ログアウト後のリダイレクト先
				'logoutRedirect' => [
						'controller' => 'Auth',
						'action' => 'login',
						'prefix' => 'admin',
				],
				// sessionストレージ設定
				'storage' => [
						'className' => 'Session',
						'key' => 'Auth.Admin'
				],
				// 許可されていないアクセスがあったときのエラーメッセージ
				'authError' => 'ログインでエラーが発生しました',
		]);
	}
}