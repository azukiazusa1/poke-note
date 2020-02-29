<?php

namespace App\Controller\Api\Users;

use App\Controller\AppController;

class FavoritesController extends AppController 
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
        $this->loadModel('Articles');
    }

    public function index()
    {
      $user_id = $this->request->getParam('user_id');
      $favorites = $this->paginate($this->Articles->find('userFavorites', ['user_id' => $user_id]));
      $paging = $this->request->getParam('paging')['Articles'];
      $this->set([
        'favorites' => $favorites,
        'paging' =>  $paging,
        '_serialize' => ['favorites', 'paging']
    ]);
    }

}