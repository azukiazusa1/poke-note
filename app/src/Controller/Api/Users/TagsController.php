<?php

namespace App\Controller\Api\Users;

use App\Controller\AppController;

class TagsController extends AppController 
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
        $user_id = $this->request->getParam('user_id');

        $tags = $this->Tags->find('byUserId', ['user_id' => $user_id]);

        $this->set([
            'tags' => $tags,
            '_serialize' => ['tags']
        ]);
    }
}