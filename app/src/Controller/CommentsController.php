<?php

namespace App\Controller;

use Cake\Http\Exception\ForbiddenException;

class CommentsController extends AppController 
{
    /**
     * @inheritDoc
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
    }


    public function add()
    {
        $comment = $this->Comments->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Comments->patchEntity($comment, $this->request->getData());
            $comment->user_id = $this->Auth->user('id');
            if ($this->Comments->save($article)) {
                $this->Flash->success('コメントを投稿しました。');
            } else {
                $this->Flash->error('コメントの投稿に失敗しました。');
            }
        }
        $this->set(compact('comment'));
        $this->redirect(['controller' => 'Articles', 'action' => 'show', $comment->article_id]);
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post']);
        $comment = $this->Comments->get($id);
        if ($this->Auth->user('id') !== $comment->user_id) {
            throw new ForbiddenException();
        }
        if ($this->Comments->delete($comment)) {
            $this->Flash->success('コメントを削除しました。');
            return $this->redirect($this->request->referer());
        } else {
            $this->Flash->success('コメントの削除に失敗しました。');
        }
    }
}