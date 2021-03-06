<?php

namespace App\Controller;

use Cake\Http\Exception\ForbiddenException;
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
        $this->Auth->allow(['index', 'latest', 'show', 'search']);
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

    public function tag()
    {
        $articles = $this->Articles->find('tagFollow', ['user_id' => $this->Auth->user('id')]);
        $this->set(compact('articles'));
        $this->render('index');
    }

    public function new()
    {
        $article = $this->Articles->newEntity();
        $article->user_id = $this->Auth->user('id');
        $this->Articles->save($article);
        return $this->redirect(['controller' => 'Articles', 'action' => 'edit', $article->id]);
    }

    public function edit($id)
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

    public function show(int $id)
    {
        $article = $this->Articles->get($id, ['contain' => ['Users', 'Comments' => ['Users'], 'Tags']]);
        if ($article->isDraft() && ($article->user_id !== $this->Auth->user('id'))) {
            throw new NotFoundException();
        }
        $isFavorite = !!$this->Favorites->find()
            ->where(['article_id' => $id, 'user_id' => $this->Auth->user('id')])
            ->first();
        $new_comment = $this->Comments->newEntity();
        $this->set('isAuthor', $article->isAuthor($this->Auth->user('id')));
        $this->set(compact('article', 'new_comment', 'isFavorite'));
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post']);
        $article = $this->Articles->get($id);
        if ($this->Auth->user('id') !== $article->user_id) {
            throw new ForbiddenException();
        }
        if ($this->Articles->delete($article)) {
            $this->Flash->success('記事を削除しました。');
            // 下書き一覧で記事を削除したとき
            if ($this->referer(null, true) === '/articles/draft') {
                return $this->redirect(['controller' => 'Articles', 'action' => 'draft']);
            }
            return $this->redirect(['controller' => 'Articles', 'action' => 'index']);
        } else {
            $this->Flash->success('記事の削除に失敗しました。');
        }
    }

    public function search()
    {
        $this->set('q', $this->request->getQuery('q'));
    }

    public function draft()
    {
        $articles = $this->Articles->find('draftByUserId', ['user_id' => $this->Auth->user('id')]);
        $this->set(compact('articles'));
    }
}