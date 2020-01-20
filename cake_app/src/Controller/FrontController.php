<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Front Controller
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 */
class FrontController extends AppController
{
	/**
	 * Initialize Method.
	 */
	public function initialize()
	{
		parent::initialize();
		$this->Gashas = TableRegistry::getTableLocator()->get("Gashas");
		$this->Cards = TableRegistry::getTableLocator()->get("Cards");
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index()
	{
		$gasha_datas = $this->Gashas->findGashaData();

		$gasha_json_data = $this->Gashas->getGashaJsonData($gasha_datas);
		$gasha_selections = Hash::combine($gasha_datas, '{n}.id', ['%s　%s', '{n}.start_date', '{n}.title']);

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
