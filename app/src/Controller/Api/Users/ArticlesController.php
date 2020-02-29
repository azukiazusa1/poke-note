<?php

namespace App\Controller\Api\Users;

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
        $this->Auth->allow(['index']);
    }

    public function index()
    {
      $user_id = $this->request->getParam('user_id');
      $articles = $this->paginate($this->Articles->find('byUserId', ['user_id' => $user_id]));
      $paging = $this->request->getParam('paging')['Articles'];
      $this->set([
        'articles' => $articles,
        'paging' => $paging,
        '_serialize' => ['articles', 'paging']
    ]);
    }
}