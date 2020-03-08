<?php

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Mailer\MailerAwareTrait;
use Token\Util\Token;

class PasswordForgotController extends AppController 
{
    use MailerAwareTrait;

    public function initialize()
	{
		parent::initialize();
        $this->Auth->allow(['index', 'reset', 'sent']);
        $this->loadModel('Users');
	}

    public function index() 
    {
    }

    public function sent()
    {
        $this->request->allowMethod(['post']);
        $email = $this->request->getData('email');
        $user = $this->Users->findByEmail($email)->first();
        if ($user) {
            $this->getMailer('User')->send('resetPassword', [$user]);
        }
        $this->set(compact('email'));
    }

    public function reset($token)
    {
        $user = $this->Users->get(Token::getId($token));
        if (!$user->tokenVerify($token)) {
            throw new NotFoundException();
        }

        if ($this->request->is('put')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success('パスワードの再設定に成功しました。');
                $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }
        $this->set(compact('user'));
    }
}
