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

        $this->assertResponseContains('@user1');

        $user = $this->viewVariable('user');

        $this->assertInstanceOf(User::class,$user);
        $this->assertSame('user1', $user->username);
    }

    public function プロフィール編集画面はログインが必要()
    {
        $this->get('/profile');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%profile');
    }
}