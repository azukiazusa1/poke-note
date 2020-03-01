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
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->Auth->allow(['index', 'add', 'delete']);
        $this->loadModel('ArticlesTags');
    }

    public function index()
    {
      $tags = $this->Tags->find('all');
      $this->set([
          'tags' => $tags,
          '_serialize' => ['tags']
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