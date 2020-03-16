<?php

namespace App\Controller\Api;

use App\Controller\AppController;

class UsersController extends AppController 
{
    /**
     * @inheritDoc
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->Auth->allow(['index']);
    }

    public function index()
    {
        $this->paginate = [
            'finder' => [
                'search' => ['params' => $this->request->getQueryParams()]
            ],
            'sortWhitelist' => [
                'total_favorite', 'username','created'
            ]
        ];
        $users = $this->paginate($this->Users);
        $paging = $this->request->getParam('paging')['Users'];
        $this->set([
            'users' => $users,
            'paging' => $paging,
            '_serialize' => ['users', 'paging']
        ]);
    }
}