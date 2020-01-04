<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Cards Controller
 *
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashaController extends AppController
{

    /**
     * Initialize Method.
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
    	// 10連(10連目はSR以上確定)
        // レアリティ抽選
        // レアリティで絞り込んだカード抽選
        $cards = $this->Cards->find()->limit(10)->toArray();

        $this->set(compact('cards'));
    }
}
