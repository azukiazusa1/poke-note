<?php

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

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
        $tag = $this->Tags->findByTitle($title)->first();
        if (!$tag) {
            throw new NotFoundException();
        }
        $isFollowed = true;
        $this->set(compact('tag', 'isFollowed'));

        $this->render('show102');
    }
   
}