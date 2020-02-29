<?php

namespace App\Controller\Api\Users;

use App\Controller\AppController;

class CommentsController extends AppController 
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
      $comments = $this->paginate($this->Comments->find('byUserId', ['user_id' => $user_id]));
      $paging = $this->request->getParam('paging')['Comments'];
      $this->set([
        'comments' => $comments,
        'paging' => $paging,
        '_serialize' => ['comments', 'paging']
      ]);
    }
}