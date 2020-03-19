<?php

namespace App\Controller\Api\Users;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\InternalErrorException;

class FollowsController extends AppController 
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

    public function index()
    {
        $user_id = $this->request->getParam('user_id');

        $follows = $this->Follows->find('follow', ['user_id' => $user_id]);
        $followers = $this->Follows->find('follower', ['user_id' => $user_id]);

        $this->set([
            'follows' => $follows,
            'followers' => $followers,
            '_serialize' => ['follows', 'followers']
        ]);
    }

    public function add()
    {
        $user_id = $this->request->getParam('user_id');

        if (!$this->Auth->user('id')) {
            throw new UnauthorizedException(__('フォローをするためにはログインが必要です。'));
        }

        if ($this->Auth->user('id') === $user_id) {
            throw new InternalErrorException(_('自分自身をフォローすることはできません。'));
        }

        $follow = $this->Follows->find()
            ->where([
                'follow_user_id' => $user_id,
                'user_id' => $this->Auth->user('id')
            ])
            ->first();
        
        if ($follow) {
            $message = 'Deleted';
            if (!$this->Follows->delete($follow))  {
                throw new BadRequestException('予期せぬエラーが発生しました。');
            }
        } else {
            $message = 'Saved';
            $follow = $this->Follows->newEntity();
            $follow->follow_user_id = $user_id;
            $follow->user_id = $this->Auth->user('id');
            if (!$this->Follows->save($follow)) {
                throw new InternalErrorException('予期せぬエラーが発生しました。');
            }
        }
        $this->set([
            'message' => $message,
            'follow' => $follow,
            '_serialize' => ['message', 'follow']
        ]);
    }
   
}