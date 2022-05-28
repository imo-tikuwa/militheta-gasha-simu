<?php
declare(strict_types=1);

namespace App\Controller\Info;

/**
 * Character Controller
 *
 * @property \App\Model\Table\CharactersTable $Characters
 */
class CharacterController extends ApiController
{
    /**
     * @return void
     */
    public function index()
    {
        $this->Characters = $this->fetchTable('Characters');
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
            '_jsonOptions' => JSON_UNESCAPED_UNICODE,
        ]);
    }
}
