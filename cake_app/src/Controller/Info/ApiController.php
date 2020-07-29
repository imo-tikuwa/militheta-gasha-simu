<?php
namespace App\Controller\Info;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Api Controller
 */
class ApiController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
    }
}
