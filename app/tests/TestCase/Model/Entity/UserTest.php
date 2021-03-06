<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\User;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Model\Entity\User Test Case
 */
class UsersTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\User
     */
    public $User;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Articles',
        'app.Follows',
        'app.Tags',
        'app.ArticlesTags'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->User = new User();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->User);

        parent::tearDown();
    }

    public function testパスワードがハッシュがされるか()
    {
        $password = 'password1';
        $this->User->password = $password;
        $hashedPassword = $this->User->password;

        // ハッシュ化済み
        $this->assertNotSame($password, $hashedPassword);

        $this->assertTrue(password_verify($password, $hashedPassword));
    }

    public function testフォローしているユーザーの場合Trueを返す()
    {
        $this->User->id = 2;
        $this->assertTrue($this->User->isFollowed(1));
    }

    public function testフォローしていないユーザーの場合Falseを返す()
    {
        $this->User->id = 2;
        $this->assertFalse($this->User->isFollowed(3));
    }

    public function test使ったことがあるタグの場合trueを返す()
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->get(1, [
            'contain' => ['Articles' => fn($q) => $q->select(['id', 'user_id'])->contain('Tags')]
        ]);

        $this->assertTrue($user->isUsedTag(1));
    }

    public function test使ったことがないタグの場合falseを返す()
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->get(1, [
            'contain' => ['Articles' => fn($q) => $q->select(['id', 'user_id'])->contain('Tags')]
        ]);

        $this->assertFalse($user->isUsedTag(2));
    }

    public function test総いいねを数える()
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->get(2, ['contain' => ['Articles']]);
        $this->assertSame(5, $user->total_favorite);
    }
}
