<?php

namespace App\Controller;

use RuntimeException;

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
        $this->Auth->allow(['signup', 'show']);
        $this->loadModel('Follows');
        $this->loadComponent('User');
	}

	public function show($username)
	{
		$user = $this->Users->find()
			->where(['username' => $username])
			->contain(['Articles'])
            ->first();
        
        if (!$user) {
            throw new NotFoundException();
        }

        $favorite_count = $this->User->countFavorite($user->articles);

		$articles = new Collection($user->articles);
        $popular_articles = $articles->sortBy('favorite_count')->take(5)->toArray();
        
        $isFollowed = $this->User->isFollowed($user->id, $this->Auth->user('id'));

		$this->set(compact('user', 'favorite_count', 'popular_articles', 'isFollowed'));
	}

	public function edit()
	{
		$id = $this->Auth->user('id');
		$user = $this->Users->get($id);
		if ($this->request->is('put')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			$file = $this->request->getData('image_file');
			try {
				if (is_uploaded_file($file['tmp_name']) && $file['error'] === 0) {
					$ext = array_search(mime_content_type($file['tmp_name']), [
						'gif' => 'image/gif',
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
					], true);

					if (!$ext) {
						throw new RuntimeException('ファイル形式が不正です。');
					}

					$filename = 'user/' . sha1_file($file['tmp_name']) . '.' . $ext;
					$path = '../webroot/img/' . $filename;

					if (!move_uploaded_file($file['tmp_name'], $path)) {
						throw new RuntimeException('ファイル保存時にエラーが発生しました。');
					}

					chmod($path, 0644);
					$user->image = $filename;
				}
			} catch (RuntimeException $e) {
				$this->Flash->error($e->getMessage());
				return $this->render();
			}
            $follows = $this->Follows->newEntity(['follow_user_id' => 6, 'user_id' => 1]);
            $this->Follows->save($follows);
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

		if ($this->request->is('post')) {
			if (!password_verify($this->request->getData('old_password'), $user->password)) {
				$this->Flash->error('現在のパスワードと一致しません。');
				return $this->render();
			} 

			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($user->errors()) {
				return $this->render();
			}

			if ($this->Users->save($user)) {
				$this->Flash->success('パスワードの変更に成功しました。');
			} else {
				$this->Flash->error('パスワードの変更に失敗しました。');
			}
		}
	}

	public function email()
	{

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
				return $this->redirect($this->Auth->logout());
			} else {
				$this->Flash->error('アカウントの削除に失敗しました。');
			}
		}
	}

	public function signup()
	{
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($user->errors()) {
				return $this->render();
			}
			$user->image = 'user/default.png';
			if ($this->Users->save($user)) {
				$this->Flash->success('ユーザー登録に成功しました。');
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error('ユーザー登録に失敗しました。');
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