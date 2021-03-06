<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;

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
        $this->paginate = [
            'finder' => [
                'search' => ['params' => $this->request->getQueryParams()]
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

    public function edit($id)
    {
        $article = $this->Articles->findById($id)->first();

        if (!$article) {
            throw new NotFoundException(__('記事が存在しません。'));
        }

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