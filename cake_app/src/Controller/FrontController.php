<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Front Controller
 *
 * @property \App\Model\Table\GashaTable $Gasha
 */
class FrontController extends AppController
{
	/**
	 * Initialize Method.
	 */
	public function initialize()
	{
		parent::initialize();
		$this->Gasha = TableRegistry::getTableLocator()->get("Gasha");
		$this->Cards = TableRegistry::getTableLocator()->get("Cards");
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index()
	{
		$gasha_datas = $this->Gasha->findGashaData();

		$gasha_json_data = $this->Gasha->getGashaJsonData($gasha_datas);
		$gasha_selections = Hash::combine($gasha_datas, '{n}.id', '{n}.title');

		$rarity_codes = _code("Cards.rarity");
		$type_codes = _code("Cards.type");

		$this->set(compact(
				'gasha_json_data',
				'gasha_selections',
				'rarity_codes',
				'type_codes'
		));
	}
}
