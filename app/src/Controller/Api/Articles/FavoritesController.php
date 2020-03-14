<?php

namespace App\Controller\Api\Articles;

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
        $article_id = $this->request->getParam('article_id');
        $favorites = $this->Favorites->findByArticleId($article_id)
            ->contain('Users', fn($q) => $q->select([
                'id', 'username', 'nickname', 'description', 'image'
            ]));
        $this->set([
            'favorites' => $favorites,
            '_serialize' => ['favorites']
        ]);
    }

    /**
     * すでにいいねが存在する場合、いいねを取り消し
     * いいねが存在しない場合新たにいいねを追加する。
     *
     */
    public function add()
    {
        $article_id = $this->request->getParam('article_id');

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
            $message = 'Deleted';
            if (!$this->Favorites->delete($favotite))  {
                throw new InternalErrorException();
            }
        } else {
            $message = 'Saved';
            $favorite = $this->Favorites->newEntity();
            $favorite->article_id = $article_id;
            $favorite->user_id = $this->Auth->user('id');
            if (!$this->Favorites->save($favorite)) {
                throw new InternalErrorException();
            }
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
   
}