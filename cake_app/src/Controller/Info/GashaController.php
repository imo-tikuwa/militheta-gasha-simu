<?php
namespace App\Controller\Info;

use App\Controller\Info\ApiController;

/**
 * Gasha Controller
 */
class GashaController extends ApiController
{
    public function index()
    {
        $this->loadModel('Gashas');
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
