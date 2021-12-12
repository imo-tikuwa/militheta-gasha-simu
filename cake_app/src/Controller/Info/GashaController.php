<?php
namespace App\Controller\Info;

use App\Controller\Info\ApiController;

/**
 * Gasha Controller
 *
 * @property \App\Model\Table\GashasTable $Gashas
 */
class GashaController extends ApiController
{
    /**
     *
     * @return void
     */
    public function index()
    {
        $this->Gashas = $this->fetchTable('Gashas');
        $results = $this->Gashas->find()
        ->select([
            'start_date',
            'end_date',
            'title',
        ])
        ->enableHydration(false)
        ->toArray();
        $this->set([
            'results' => $results,
            '_serialize' => 'results',
            '_jsonOptions' => JSON_UNESCAPED_UNICODE
        ]);
    }
}
