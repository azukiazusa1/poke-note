<?php

namespace App\Controller\Api\Articles;

use App\Controller\AppController;

class TagsController extends AppController 
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
        $this->loadModel('ArticlesTags');
    }

    public function add()
    {
		$article_id = $this->request->getParam('article_id');
        $tag_id = $this->request->getData('data');
        $message = 'Saved';

		$articles_tags = $this->ArticlesTags->newEntity();
		$articles_tags->tag_id = $tag_id;
		$articles_tags->article_id = $article_id;

		$this->ArticlesTags->save($articles_tags);
        $this->set([
            'message' => $message,
            'articles_tags' => $articles_tags,
            '_serialize' => ['message', 'articles_tags']
        ]);
    }

    public function delete($tag_id)
    {
        $article_id = $this->request->getParam('article_id');

        $message = 'Deleted';

        $article_tags = $this->ArticlesTags->find()
            ->where(['tag_id' => $tag_id])
            ->where(['article_id' => $article_id])
            ->first();
        if ($article_tags) {
            if (!$this->ArticlesTags->Delete($article_tags)) {
                $message = 'Error';
            }
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
   
}