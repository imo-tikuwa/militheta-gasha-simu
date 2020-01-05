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
        // 単発
        // 10連(10連目はSR以上確定)
        // フェス
        // ピックアップ
        // タイプガシャ
        // 最初にレアリティ抽選
        // 次にレアリティで絞り込んだカード抽選
        // その他、限定を除外など
        $cards = $this->Cards->find()->limit(10)->toArray();

        $this->set(compact('cards'));
    }
}
