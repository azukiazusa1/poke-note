<?php

namespace App\Controller;

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
        $this->Auth->allow(['add']);
    }

    public function add(string $title = null)
    {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $this->response->type('json');
            $tag = $this->Tags->find()
                ->where(['title' => $title])
                ->first();
            if (!isset($tag)) {
                $tag = $this->Tags->newEntity();
                $tag->title = $title;
                $this->Tags->save($tag);
            }
            $this->response->body(json_encode($tag));
        }
    }

    public function search(string $title = null)
    {
        $this->set(compact('title'));
    }
   
}