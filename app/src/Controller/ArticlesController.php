<?php

namespace App\Controller;

use Cake\Http\Exception\ForbiddenException;

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
        $this->Auth->allow(['index', 'show']);
    }

    /**
     * 記事一覧画面
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles->find('all')->contain(['Users']));
        $this->set(compact('articles'));
    }

    public function new()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData(), ['associated' => ['Tags']]);
            $article->user_id = $this->Auth->user('id');
            if ($this->Articles->save($article)) {
                $this->Flash->success('投稿に成功しました。');
                $this->redirect(['controller' => 'Articles', 'action' => 'show', $article->id]);
            }
        }
        $this->set(compact('article'));
        $this->render('edit');
    }

    public function show(int $id = null)
    {
        $article = $this->Articles->get($id, ['contain' => ['Users', 'Tags']]);
        $this->set(compact('article'));
    }

    public function edit($id = null)
    {
        $article = $this->Articles->get($id, ['contain' => 'Tags']);
        if ($this->Auth->user('id') !== $article->user_id) {
            throw new ForbiddenException();
        }
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->user_id = $this->Auth->user('id');
            if ($this->Articles->save($article)) {
                $this->Flash->success('投稿に成功しました。');
                $this->redirect(['controller' => 'Articles', 'action' => 'show', $article->id]);
            }
        }
        $this->set(compact('article'));

    }
}