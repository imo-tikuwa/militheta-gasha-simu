<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * APIの共通処理をまとめたコントローラ
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 */
class ApiController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->disableAutoLayout();
        $this->autoRender = false;
        $this->response->withCharset('UTF-8');
        $this->response->withType('application/json');

        $this->Gashas = TableRegistry::getTableLocator()->get('Gashas');
        $this->Cards = TableRegistry::getTableLocator()->get('Cards');
    }
}
