<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
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
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Gashas = $this->fetchTable('Gashas');
        $this->Cards = $this->fetchTable('Cards');

        $this->viewBuilder()->setLayout('default_front');
    }

    /**
     * 共通処理
     *
     * @param \Cake\Event\EventInterface $event EventInterface
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $gasha_datas = $this->Gashas->findGashaData();
        $gasha_json_data = $this->Gashas->getGashaJsonData($gasha_datas);
        $gasha_selections = Hash::combine($gasha_datas, '{n}.id', ['%s　%s', '{n}.start_date', '{n}.title']);

        $rarity_codes = _code('Codes.Cards.rarity');
        $type_codes = _code('Codes.Cards.type');

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
