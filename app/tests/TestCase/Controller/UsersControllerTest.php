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
        'app.Users',
        'app.Articles',
        'app.Comments',
        'app.Favorites',
        'app.Follows'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function tearDown(): void
    {
        unset($this->Users);
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

        $this->assertResponseContains('<h1>プロフィール編集');

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

        $this->assertResponseContains('<h1>パスワード');
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
        $this->assertResponseContains('<h1>メールアドレス');
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

    public function testユーザー削除画面()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->get('/users/delete');
        $this->assertResponseOk();

        $this->assertResponseContains('<h1 class="red-text">アカウントを削除');
    }

    public function testユーザー削除画面はログインが必要()
    {
        $this->get('/users/delete');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Fusers%2Fdelete');
    }

    public function testユーザー削除成功()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'password' => 'password1',
        ];
        $this->post('/users/delete', $data);

        $this->assertRedirect('/');
        // ログアウトされている
        $this->assertSession([], 'Auth');
        $this->assertEmpty($this->Users->findById(4)->first());
        $this->assertFlashMessage('アカウントを削除いたしました。今までのご利用ありがとうございました。');
        $this->assertFlashElement('Flash/success');
    }

    public function testユーザー削除画面現在のパスワードと一致しない()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'password' => 'password',
        ];
        $this->post('/users/delete', $data);
        $this->assertFlashMessage('現在のパスワードと一致しません。');
        $this->assertFlashElement('Flash/error');
        // 削除は実行されていない
        $this->assertNotEmpty($this->Users->get(4));
    }

    public function testログインページが表示される()
    {
        $this->get('/login');
        $this->assertResponseOk();
        $this->assertResponseContains('<h1>ログイン');
    }

    public function testログイン成功()
    {
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'username' => 'hashedPassUser', 
            'password' => 'password1',
        ];
        $this->post('/login', $data);
        $this->assertRedirect('/');
        $this->assertSession(4, 'Auth.User.id');
    }

    public function testログイン失敗()
    {
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'username' => 'hashedPassUser', 
            'password' => 'password',
        ];

        $this->post('/login', $data);
        $this->assertFlashMessage('ユーザー名またはパスワードが間違っています。');
        $this->assertFlashElement('Flash/error');
    }

    public function testログアウト()
    {
        $this->session(['Auth.User.id' => 4]);

        $this->get('/logout');

        $this->assertSession([], 'Auth');
        $this->assertRedirect('/');
    }

    public function testユーザー登録ページが表示される()
    {
        $this->get('/signup');
        $this->assertResponseOk();
        $this->assertResponseContains('<h1>ユーザー登録');
    }

    public function testユーザー登録成功()
    {
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'username' => 'newUser', 
            'email' => 'example@gmail.com',
            'password' => 'password1',
        ];

        $this->post('/signup', $data);
        $this->assertRedirect('/');
        $this->assertSession(5, 'Auth.User.id');
        $this->assertNotEmpty($this->Users->get(5));
        $this->assertFlashMessage('ユーザー登録に成功しました。');
        $this->assertFlashElement('Flash/success');
    }

    public function testユーザー登録失敗()
    {
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'username' => '', 
            'email' => '',
            'password' => '',
        ];

        $this->post('/signup', $data);
        $this->assertFlashMessage('ユーザー登録に失敗しました。');
        $this->assertFlashElement('Flash/error');
        $this->assertResponseContains('ユーザー名が入力されていません。');
        $this->assertResponseContains('メールアドレスが入力されていません。');
        $this->assertResponseContains('パスワードが入力されていません。');
    }

}