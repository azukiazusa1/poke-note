<?php
namespace App\Mailer;

use App\Model\Entity\User;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;

class UserMailer extends Mailer
{
    public function resetPassword(User $user)
    {
        $url = Router::url(['controller' => 'PasswordForgot', 'action' => 'reset',  $user->tokenGenerate()], true);
        $this
            ->setTo($user->email)
            ->setSubject('パスワードリセット')
            ->set([
                'url' => $url,
                'username' => $user->username
            ]);
    }

    public function changePassword(User $user)
    {
        $this
            ->setTo($user->email)
            ->setSubject('ご利用のパスワードがリセットされました。')
            ->set(['username' => $user->username]);
    }
}