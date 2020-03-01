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

        $filename = $this->File->upload($image['image'], 'article');

        $this->set([
            'image' => $image['image']['tmp_name'],
            'filename' => '/img/' . $filename,
            '_serialize' => ['image', 'filename']
        ]);
    }
   
}