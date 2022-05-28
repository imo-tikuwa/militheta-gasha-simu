<?php
declare(strict_types=1);

namespace App\Controller\Info;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Api Controller
 */
class ApiController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
    }
}
