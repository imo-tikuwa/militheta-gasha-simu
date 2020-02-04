<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * APIの共通処理をまとめたコントローラ
 */
class ApiController extends AppController
{

	/**
	 * Initialize Method.
	 */
	public function initialize()
	{
		parent::initialize();
		$this->viewBuilder()->enableAutoLayout(false);
		$this->autoRender = false;
		$this->response->withCharset('UTF-8');
		$this->response->withType('application/json');

		$this->Gashas = TableRegistry::getTableLocator()->get("Gashas");
		$this->Cards = TableRegistry::getTableLocator()->get("Cards");
	}

}
