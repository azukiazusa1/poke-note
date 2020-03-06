<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use App\Model\Entity\User;

class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public $fixtures = [
        'app.Articles', 
        'app.Users', 
        'app.Tags', 
        'app.ArticlesTags', 
        'app.Follows',
        'app.Comments',
        'app.Favorites'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->Articles = TableRegistry::getTableLocator()->get('Users');
    }

    public function testユーザー詳細画面が表示される()
    {
        $this->get('/users/user1');

        $this->assertResponseOk();
        $this->assertResponseContains('@user1');

        $user = $this->viewVariable('user');

        $this->assertInstanceOf(User::class,$user);
        $this->assertSame('user1', $user->username);
    }

    public function test存在しないユーザー名()
    {
        $this->get('/users/notExsitsUser');
        $this->assertResponseCode(404);
    }

    public function プロフィール編集画面()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/profile');
        $this->assertResponseOk();

        $this->assertResponseContains('<h4>プロフィール編集');

        $user = $this->viewVariable('user');

        $this->assertInstanceOf(User::class,$user);
        $this->assertSame('user1', $user->username);
    }

    public function testプロフィール編集画面はログインが必要()
    {
        $this->get('/profile');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Fprofile');
    }

    public function testプロフィール編集ができる()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $file = [
            'error' => 0,
            'name' => '',
            'size' => '',
            'tmp_name' => '',
            'type' => '',
        ];
        $this->configRequest([
            'Content-Type' => 'multipart/form-data',
        ]);

        $data = [
            'nickname' => 'nickname',
            'link' => 'https://google.com',
            'description' => 'lorem ipsm',
            'image_file' => $file
        ];

        $this->put('/profile', $data);
        $this->assertResponseOk();
        $this->assertFlashMessage('プロフィール編集に成功しました。');
        $this->assertFlashElement('Flash/success');
        $user = $this->viewVariable('user');
        $this->assertSame('nickname', $user->nickname);
        $this->assertSame('https://google.com', $user->link);
        $this->assertSame('lorem ipsm', $user->description);
    }

    public function testプロフィール編集失敗()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $file = [
            'error' => 0,
            'name' => '',
            'size' => '',
            'tmp_name' => '',
            'type' => '',
        ];
        $this->configRequest([
            'Content-Type' => 'multipart/form-data',
        ]);

        $data = [
            'nickname' => 'nickname',
            'link' => 'htt://google.com',
            'description' => 'lorem ipsm',
            'image_file' => $file
        ];

        $this->put('/profile', $data);

        $this->assertFlashMessage('プロフィール編集に失敗しました。');
        $this->assertFlashElement('Flash/error');
    }

    public function パスワード変更画面()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/password');

        $this->assertResponseContains('<h4>パスワード');
    }

    public function パスワード変更画面はログイン時のみ()
    {
        $this->get('/password');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Fpassword');
    }

}