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
        $this->Auth->allow(['index', 'latest', 'show']);
        $this->loadModel('Comments');
        $this->loadModel('Favorites');
    }

    /**
     * 記事一覧画面
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $articles = $this->Articles->find('trend');
        $this->set(compact('articles'));
    }

    public function latest()
    {
        $articles = $this->Articles->find('latest');
        $this->set(compact('articles'));
        $this->render('index');
    }

    public function timeline()
    {
        $articles = $this->Articles->find('timeline', ['user_id' => $this->Auth->user('id')]);
        $this->set(compact('articles'));
        $this->render('index');
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
        $article = $this->Articles->get($id, ['contain' => ['Users', 'Comments' => ['Users'], 'Tags']]);
        $isFavorite = !!$this->Favorites->find()
            ->where(['article_id' => $id, 'user_id' => $this->Auth->user('id')])
            ->first();
        $new_comment = $this->Comments->newEntity();
        $isAuthor = ($this->Auth->user('id') === $article->user_id);
        $this->set(compact('article', 'new_comment', 'isAuthor', 'isFavorite'));
    }

    public function edit($id = null)
    {
        $article = $this->Articles->get($id, ['contain' => 'Tags']);
        if ($this->Auth->user('id') !== $article->user_id) {
            throw new ForbiddenException();
        }
        if ($this->request->is('put')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->user_id = $this->Auth->user('id');
            if ($this->Articles->save($article)) {
                $this->Flash->success('更新に成功しました。');
                $this->redirect(['controller' => 'Articles', 'action' => 'show', $article->id]);
            }
        }
        $this->set(compact('article'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post']);
        $article = $this->Articles->get($id);
        if ($this->Auth->user('id') !== $article->user_id) {
            throw new ForbiddenException();
        }
        if ($this->Articles->delete($article)) {
            $this->Flash->success('記事を削除しました。');
            return $this->redirect(['controller' => 'Articles', 'action' => 'index']);
        } else {
            $this->Flash->success('記事の削除に失敗しました。');
        }
    }

    public function search()
    {
        $q = $this->request->getQuery('q');
        $this->set(compact('q'));
    }
}