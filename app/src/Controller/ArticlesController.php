<?php

namespace App\Controller;

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
        $this->Auth->allow('index');
    }

    /**
     * 記事一覧画面
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles->find('all'));

        $this->set(compact('articles'));
    }
}