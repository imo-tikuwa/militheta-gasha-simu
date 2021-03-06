<?php
namespace App\Controller\Info;

use App\Controller\Info\ApiController;

/**
 * Character Controller
 */
class CharacterController extends ApiController
{
    /**
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Characters');
        $results = $this->Characters->find()
        ->select([
            'id',
            'name',
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
