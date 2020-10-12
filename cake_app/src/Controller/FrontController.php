<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Event\EventInterface;

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
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Gashas = TableRegistry::getTableLocator()->get("Gashas");
        $this->Cards = TableRegistry::getTableLocator()->get("Cards");
    }

    /**
     * {@inheritDoc}
     * 共通処理
     * @param EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $gasha_datas = $this->Gashas->findGashaData();
        $gasha_json_data = $this->Gashas->getGashaJsonData($gasha_datas);
        $gasha_selections = Hash::combine($gasha_datas, '{n}.id', ['%s　%s', '{n}.start_date', '{n}.title']);

        $rarity_codes = _code("Codes.Cards.rarity");
        $type_codes = _code("Codes.Cards.type");

        $this->set(compact(
            'gasha_json_data',
            'gasha_selections',
            'rarity_codes',
            'type_codes'
        ));
    }

    /**
     * 通常モード
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
    }

    /**
     * ピック対象を引くまでに何連掛かるかモード
     *
     * @return \Cake\Http\Response|void
     */
    public function targetPick()
    {
        $this->viewBuilder()->setLayout('target_pick');
    }
}
