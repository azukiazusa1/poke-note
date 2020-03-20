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

        $this->paginate = [
            'limit' => 100,
            'order' => [
                'article_count' => 'desc'
            ],
            'finder' => [
                'byUserId' => ['user_id' => $user_id]
            ]
        ];

        $tags = $this->paginate($this->Tags);
        $paging = $this->request->getParam('paging')['Tags'];

        $this->set([
            'tags' => $tags,
            'paging' => $paging,
            '_serialize' => ['tags', 'paging']
        ]);
    }
}