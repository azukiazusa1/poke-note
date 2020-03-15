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

    public function index()
    {
    }

    public function show(string $title)
    {
        $this->set(compact('title'));
    }
   
}