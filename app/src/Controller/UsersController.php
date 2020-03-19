<?php

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Collection\Collection;

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
        $this->Auth->allow(['signup', 'show', 'index']);
        $this->loadModel('Follows');
        $this->loadComponent('File');
        $this->loadComponent('LoginCookie');
    }
    
    public function index()
    {
        $this->Users->find('userRanking')->toArray();
    }

	public function show($username)
	{
		$user = $this->Users->find()
			->where(['username' => $username])
			->contain('Articles', fn($q) => $q->find('published'))
      		->first();
        
		if (!$user) {
			throw new NotFoundException();
		}

		$articles = new Collection($user->articles);
		$popular_articles = $articles->sortBy('favorite_count')->take(5)->toArray();
		
		$isFollowed = $user->isFollowed($this->Auth->user('id'));
		$this->set(compact('user', 'popular_articles', 'isFollowed'));
	}

	public function edit()
	{
		$id = $this->Auth->user('id');
		$user = $this->Users->get($id);
		if ($this->request->is('put')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			$filename = $this->File->upload($this->request->getData('image_file'), 'user');
			if(isset($filename)) {
				$user->image = $filename;
			}
			if ($this->Users->save($user)) {
				$this->Flash->success('プロフィール編集に成功しました。');
			} else {
				$this->Flash->error('プロフィール編集に失敗しました。');
			}
		}
		$this->set(compact('user'));
	}

	public function password()
	{
		$id = $this->Auth->user('id');
		$user = $this->Users->get($id);
		$this->set(compact('user'));

		if ($this->request->is('put')) {
			if (!password_verify($this->request->getData('old_password'), $user->password)) {
				$this->Flash->error('現在のパスワードと一致しません。');
				return $this->render();
			} 

			$user = $this->Users->patchEntity($user, $this->request->getData());

			if ($this->Users->save($user)) {
				$this->Flash->success('パスワードの変更に成功しました。');
			} else {
				$this->Flash->error('パスワードの変更に失敗しました。');
			}
		}
	}

	public function email()
	{
        $id = $this->Auth->user('id');
		$user = $this->Users->get($id);
		$this->set(compact('user'));

		if ($this->request->is('put')) {
			if (!password_verify($this->request->getData('co_password'), $user->password)) {
				$this->Flash->error('現在のパスワードと一致しません。');
				return $this->render();
			} 

			$user = $this->Users->patchEntity($user, $this->request->getData());

			if ($this->Users->save($user)) {
				$this->Flash->success('メールアドレスの変更に成功しました。');
			} else {
				$this->Flash->error('メールアドレスの変更に失敗しました。');
			}
		}
	}

	public function delete()
	{
		$id = $this->Auth->user('id');
		$user = $this->Users->get($id);
		$this->set(compact('user'));
		if ($this->request->is('post')) {
			if (!password_verify($this->request->getData('password'), $user->password)) {
				$this->Flash->error('現在のパスワードと一致しません。');
				return $this->render();
			} 

			if ($this->Users->delete($user)) {
                $this->Flash->success('アカウントを削除いたしました。今までのご利用ありがとうございました。');
                $this->LoginCookie->delete();
				return $this->redirect($this->Auth->logout());
			} else {
				$this->Flash->error('アカウントの削除に失敗しました。');
			}
		}
	}

	public function signup()
	{
		// すでにログイン済み
		if ($this->Auth->user('id')) {
			return $this->redirect(['controller' => 'Articles', 'action' => 'index']);
		}
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			$user->image = 'user/default.png';
			if ($this->Users->save($user)) {
				$this->Flash->success('ユーザー登録に成功しました。');
                $this->Auth->setUser($user);
                $this->LoginCookie->generate($user);
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error('ユーザー登録に失敗しました。');
			}
		}
		$this->set(compact('user'));
	}

	public function login()
	{
		// すでにログイン済み
		if ($this->Auth->user('id')) {
			return $this->redirect(['controller' => 'Articles', 'action' => 'index']);
		}
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				$user = $this->Users->get($user['id']);
				$this->LoginCookie->generate($user);
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error(__('ユーザー名またはパスワードが間違っています。'));
			}
		}
	}

	public function logout()
	{
    	$this->LoginCookie->delete();
			return $this->redirect($this->Auth->logout());
	}
}