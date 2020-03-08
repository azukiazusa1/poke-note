<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Routing\Router;

class UserMailer extends Mailer
{
    public function resetPassword($user)
    {
        $url = Router::url(['controller' => 'PasswordForgot', 'action' => 'reset',  $user->tokenGenerate()], true);
        $this
            ->to($user->email)
            ->subject('パスワードリセット')
            ->set([
                'url' => $url,
                'username' => $user->username
            ]);
    }
}