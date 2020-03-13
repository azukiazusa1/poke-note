<?php
namespace App\Test\TestCase\Mailer;

use Cake\TestSuite\EmailTrait;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Routing\Router;

class MyTestCase extends TestCase
{
    use EmailTrait;
    use MailerAwareTrait;

    public $fixtures = [
        'app.Users', 
    ];

    public function testメール()
    {
        $user = TableRegistry::getTableLocator()->get('Users')->get(1);
        $url = Router::url(['controller' => 'PasswordForgot', 'action' => 'reset',  $user->tokenGenerate()], true);
        $this->getMailer('User')->send('resetPassword', [$user]);

        $this->assertMailSentTo($user->email);
        $this->assertMailContains(
            `{$user->username} . 'さん
            {$url}
            パスワードのリセットは、以下のURLより10分以内に行ってください。
            なお、パスワードのリセットにお心当たりが無い場合はこのメールを無視してください。`
        );
    }
}