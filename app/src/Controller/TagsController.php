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
        $this->Auth->allow(['index','show']);
    }

    public function show(string $title = null)
    {
        $this->set(compact('title'));
    }
   
}