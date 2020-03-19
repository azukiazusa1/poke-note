<?php

namespace App\Controller\Api\Tags;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\InternalErrorException;

class UsersController extends AppController 
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
        $this->loadModel('UsersTags');
    }

    public function index()
    {
        $message = 'aaa';
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);

    }

    public function add()
    {
        $tag_id = $this->request->getParam('tag_id');

        if (!$this->Auth->user('id')) {
            throw new UnauthorizedException(__('フォローをするためにはログインが必要です。'));
        }

        $follow = $this->UsersTags->find()
            ->where([
                'tag_id' => $tag_id,
                'user_id' => $this->Auth->user('id')
            ])
            ->first();
        
        if ($follow) {
            $message = 'Deleted';
            if (!$this->UsersTags->delete($follow))  {
                throw new InternalErrorException('予期せぬエラーが発生しました。');
            }
        } else {
            $message = 'Saved';
            $follow = $this->UsersTags->newEntity();
            $follow->tag_id = $tag_id;
            $follow->user_id = $this->Auth->user('id');
            if (!$this->UsersTags->save($follow)) {
                throw new InternalErrorException('予期せぬエラーが発生しました。');
            }
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
   
}