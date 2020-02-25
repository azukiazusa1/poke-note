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
        $this->Auth->allow(['index','add']);
    }

    public function search(string $title = null)
    {
        $this->set(compact('title'));
    }
   
}