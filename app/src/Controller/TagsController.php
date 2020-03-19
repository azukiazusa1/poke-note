<?php

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\UnauthorizedException;

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
        $this->loadModel('Users');
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
        $isFollowed = false;
        if ($this->Auth->user('id')) {
            $user = $this->Users->get($this->Auth->user('id'), [
                'contain' => ['Articles' => fn($q) => $q->select(['id', 'user_id'])->contain('Tags')]
            ]);

            $isUsedTag = $user->isUsedTag($tag->id);
            $isFollowed = $user->isFollowedTag($tag->id);
        }
        $this->set(compact('tag', 'isFollowed', 'isUsedTag'));

    }

    public function edit(int $id)
    {
        $tag = $this->Tags->get($id);
        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['Articles' => fn($q) => $q->select(['id', 'user_id'])->contain('Tags')]
        ]);

        // タグの編集権限がない場合
        if (!$user->isUsedTag($tag->id)) {
            throw new UnauthorizedException();
        }

        $tag = $this->Tags->patchEntity($tag, $this->request->getData());
        if ($this->Tags->save($tag)) {
            $this->Flash->success(__('タグの説明の更新に成功しました。'));
        } else {
            $this->Flash->error(__('タグの説明の更新に失敗しました。'));
        }

        return $this->redirect($this->request->referer());

    }
   
}