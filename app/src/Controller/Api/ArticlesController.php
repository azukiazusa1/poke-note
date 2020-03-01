<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\InternalErrorException;

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
      $articles = $this->Articles->find('all');
      $this->set([
          'articles' => $articles,
          '_serialize' => ['articles']
      ]);
    }

    public function edit($id)
    {
        $article = $this->Articles->get($id);

        if ($article->user_id !== $this->Auth->user('id')) {
            throw new UnauthorizedException('記事を更新する権限がありません。');
        }
        $this->Articles->patchEntity($article, $this->request->getData());
        if (!$this->Articles->save($article)) {
            throw new InternalErrorException('予期せぬエラーが発生しました。');
        }
        $message = 'Saved';
        $this->set([
            'message' => $message,
            'article' => $article,
            '_serialize' => ['message', 'article']
        ]);
    }
   
}