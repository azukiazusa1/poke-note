<?php

namespace App\Controller;

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
		$this->Auth->allow('signup');
	}

	/**
	 * 記事一覧画面
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index()
	{
		$articles = $this->paginate($this->Articles->find('all'));

		$this->set(compact('articles'));
	}

	public function signup()
	{
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($this->Users->save($user)) {
				$this->Flash->success('ユーザー登録に成功しました。');
			} else {
				$this->Flash->success('ユーザー登録に成功しました。');
			}
		}
		$this->set(compact($user));
	}

	public function login()
	{
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error(__('Username or password is incorrect'));
			}
		}
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}
}