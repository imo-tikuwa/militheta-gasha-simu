<?php
namespace App\Controller\Info;

use App\Controller\Info\ApiController;
use Cake\Database\Query;

/**
 * Card Controller
 */
class CardController extends ApiController
{
    /**
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Cards');
        $results = $this->Cards->find()
        ->select([
            'name',
            'rarity',
            'add_date',
            'gasha_include',
            'limited',
        ])
        ->contain([
            'Characters' => function (Query $q) {
                return $q->select([
                    'name',
                ]);
            },
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
