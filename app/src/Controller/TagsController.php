<?php

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

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
        $isUsedTag = false;
        if ($this->Auth->user('id')) {
            $user = TableRegistry::getTableLocator()->get('Users')->get($this->Auth->user('id'), [
                'contain' => ['Articles' => fn($q) => $q->select(['id', 'user_id'])->contain('Tags')]
            ]);

            $isUsedTag = $user->isUsedTag($tag->id);
        }
        $isFollowed = true;
        $this->set(compact('tag', 'isFollowed', 'isUsedTag'));

    }
   
}