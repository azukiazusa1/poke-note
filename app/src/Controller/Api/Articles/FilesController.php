<?php

namespace App\Controller\Api\Articles;

use App\Controller\AppController;

class FilesController extends AppController 
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
        $this->loadComponent('File');
        $this->Auth->allow(['index', 'add', 'delete']);
    }

    public function index()
    {
        $this->set([
            'message' => 'aaa',
            'tag' => 'bbb',
            '_serialize' => ['message', 'tag']
        ]);
    }

    public function add()
    {
        $article_id = $this->request->getParam('article_id');
        $image = $this->request->getData();

        $dir = 'article/' . $article_id;

        $filename = $this->File->upload($image['image'], $dir);

        $this->set([
            'image' => $image['image']['tmp_name'],
            'filename' => $filename,
            '_serialize' => ['image', 'filename']
        ]);
    }
   
}