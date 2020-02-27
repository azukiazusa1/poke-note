<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\InternalErrorException;

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
        $this->Auth->allow(['index', 'add', 'delete']);
    }

    public function index()
    {
        $this->set([
            'message' => 'aaa',
            'tag' => 'bbb',
            '_serialize' => ['message', 'tag']
        ]);
    }

    public function add()
    {
        $article_id = $this->request->getParam('article_id');
        $message = 'Saved';

        if (!$this->Auth->user('id')) {
            throw new UnauthorizedException(__('いいねをするためにはログインが必要です。'));
        }

        $favotite = $this->Favorites->find()
            ->where([
                'article_id' => $article_id,
                'user_id' => $this->Auth->user('id')
            ])
            ->first();
        
        if ($favotite) {
            if (!$this->Favorites->delete($favotite))  {
                throw new InternalErrorException();
            }
        } else {
            $favorite = $this->Favorites->newEntity();
            $favorite->article_id = $article_id;
            $favorite->user_id = $this->Auth->user('id');
            if (!$this->Favorites->save($favorite)) {
                throw new InternalErrorException();
            }
        }
        $this->set([
            'message' => $message,
            'favotite' => $favotite,
            '_serialize' => ['message', 'favotite']
        ]);
    }
   
}