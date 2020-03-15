<?php

namespace App\Controller\Api\Tags;

use App\Controller\AppController;

class ArticlesController extends AppController 
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
        $this->Auth->allow(['index', 'add', 'delete', 'edit']);
    }

    public function index()
    {
        $tag_id = $this->request->getParam('tag_id');
        $this->paginate = [
            'finder' => [
                'byTagId' => ['tag_id' => $tag_id]
            ]
        ];
        $articles = $this->paginate($this->Articles);
        $paging = $this->request->getParam('paging')['Articles'];
        $this->set([
            'articles' => $articles,
            'paging' => $paging,
            '_serialize' => ['articles', 'paging']
        ]);
    }
   
}