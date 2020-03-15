<?php

namespace App\Controller\Api;

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
        $this->paginate = [
            'limit' => 100,
            'order' => [
                'article_count' => 'desc'
            ],
            'finder' => [
                'search' => ['params' => $this->request->getQueryParams()]
            ]
        ];
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->Auth->allow(['index', 'add', 'delete']);
        $this->loadModel('ArticlesTags');
    }

    public function index()
    {
      $tags = $this->paginate($this->Tags);
      $paging = $this->request->getParam('paging')['Tags'];
      $this->set([
          'tags' => $tags,
          'paging' => $paging,
          '_serialize' => ['tags', 'paging']
      ]);
    }

    public function add()
    {
        $title = $this->request->getData('data');
        $message = 'Saved';

        $tag = $this->Tags->findOrCreate(['title' => $title]);
        $this->set([
            'message' => $message,
            'tag' => $tag,
            '_serialize' => ['message', 'tag']
        ]);
    }

}