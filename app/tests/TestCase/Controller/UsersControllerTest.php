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

    public function testパスワード変更画面()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/password');

        $this->assertResponseContains('<h4>パスワード');
    }

    public function testパスワード変更画面はログイン時のみ()
    {
        $this->get('/password');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Fpassword');
    }

    public function testパスワード変更成功()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'old_password' => 'password1',
            'password' => 'password2',
        ];
        $this->put('/password', $data);
        $this->assertResponseok();

        $this->assertFlashMessage('パスワードの変更に成功しました。');
        $this->assertFlashElement('Flash/success');
    }

    public function testパスワード変更画面もとのパスワードが間違っている()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'old_password' => 'password',
            'password' => 'password2',
        ];
        $this->put('/password', $data);
        $this->assertFlashMessage('現在のパスワードと一致しません。');
        $this->assertFlashElement('Flash/error');
    }

    public function testパスワード変更失敗()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'old_password' => 'password1',
            'password' => 'password',
        ];
        $this->put('/password', $data);
        $this->assertFlashMessage('パスワードの変更に失敗しました。');
        $this->assertFlashElement('Flash/error');
        $this->assertResponseContains('パスワードは英文字、数字それぞれ1文字以上含める必要があります。');
    }

    public function testメールアドレス変更画面()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->get('/email');

        $this->assertResponseOk();
        $this->assertResponseContains('<h4>メールアドレス');
    }

    public function testメールアドレス変更画面はログインが必要()
    {
        $this->get('/email');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Femail');
    }

    public function testメールアドレス変更成功()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'password' => 'password1',
            'email' => 'new@gmail.com',
        ];
        $this->put('/email', $data);
        $user = $this->viewVariable('user');
        $this->assertSame('new@gmail.com', $user->email);
        $this->assertFlashMessage('メールアドレスの変更に成功しました。');
        $this->assertFlashElement('Flash/success');
    }

    public function testメールアドレス変更画面もとのパスワードが間違っている()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'password' => 'password',
            'email' => 'aaa@gmail.com',
        ];
        $this->put('/email', $data);
        $this->assertFlashMessage('現在のパスワードと一致しません。');
        $this->assertFlashElement('Flash/error');
    }

    public function testメールアドレス変更画面メールアドレスの変更に失敗()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'password' => 'password1',
            'email' => 'aaagmail.com',
        ];
        $this->put('/email', $data);
        $this->assertFlashMessage('メールアドレスの変更に失敗しました。');
        $this->assertFlashElement('Flash/error');
        $this->assertResponseContains('メールの形式が正しくありません。');
    }

}