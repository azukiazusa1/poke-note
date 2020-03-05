<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Articles',
        'app.Comments',
        'app.Favorites',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testバリデーションエラーがないとき()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'nickname' => str_repeat('a', 32),
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
            'desctiption' => str_repeat('a', 255),
            'link' => 'https://google.com'
        ]);

        $expected = [];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testニックネーム、プロフィール、リンクは空でもok()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'nickname' => '',
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
            'desctiption' => '',
            'link' => ''
        ]);

        $expected = [];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testユーザー名は32文字以内()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 33),
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
        ]);

        $expected = ['username' => [
            'maxLength' => 'ユーザー名は32文字までです。'
        ]];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testユーザー名未入力()
    {
        $user = $this->Users->newEntity([
            'username' => '',
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
        ]);

        $expected = ['username' => [
            '_empty' => 'ユーザー名が入力されていません。'
        ]];
        $this->assertSame($expected, $user->getErrors());   
    }

    public function testユーザー名は英数字ハイフンのみ()
    {
        $user = $this->Users->newEntity([
            'username' => 'あああ',
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
        ]);

        $expected = ['username' => [
            'username' => 'ユーザー名は英数字-_のみ使用できます。'
        ]];
        $this->assertSame($expected, $user->getErrors());   

        $user = $this->Users->newEntity([
            'username' => '_-Az19',
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
        ]);

        $expected = [];
        $this->assertSame($expected, $user->getErrors());   
    }

    public function testパスワードは6文字以上()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => 'A1234',
        ]);

        $expected = ['password' => [
            'lengthBetween' => 'パスワードは6文字以上20文字以下にする必要があります。'
        ]];
        $this->assertSame($expected, $user->getErrors());

        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => 'A12345',
        ]);

        $expected = [];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testパスワードは20文字以下()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => 'A' . str_repeat('1', 20),
        ]);

        $expected = ['password' => [
            'lengthBetween' => 'パスワードは6文字以上20文字以下にする必要があります。'
        ]];
        $this->assertSame($expected, $user->getErrors());

        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => 'A' . str_repeat('1', 19),
        ]);

        $expected = [];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testパスワードは英数字のみ()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => 'A11あいうえお'
        ]);

        $expected = ['password' => [
            'alphaNumeric' => 'パスワードには半角英数字のみ使用できます。'
        ]];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testパスワードは英文字数字それぞれ一文字以上()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => 'password'
        ]);

        $expected = ['password' => [
            'requreAlphaNumeric' => 'パスワードは英文字、数字それぞれ1文字以上含める必要があります。'
        ]];
        $this->assertSame($expected, $user->getErrors());

        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'email' => 'aaa@example.com',
            'password' => '123456'
        ]);

        $expected = ['password' => [
            'requreAlphaNumeric' => 'パスワードは英文字、数字それぞれ1文字以上含める必要があります。'
        ]];
        $this->assertSame($expected, $user->getErrors());
    }

    public function testリンクがURLNo形式じゃないとき()
    {
         // urlの形式じゃない時
         $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'nickname' => str_repeat('a', 32),
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
            'desctiption' => str_repeat('a', 255),
            'link' => 'htt://google.com'
        ]);

        $expected = ['link' => [
            'urlWithProtocol' => 'リンクはURLの形式である必要があります。'
        ]];
        $this->assertSame($expected, $user->getErrors());
    }
    
    public function testリンクが255文字以上()
    {
        $user = $this->Users->newEntity([
            'username' => str_repeat('a', 32),
            'nickname' => str_repeat('a', 32),
            'password' => 'A1234567',
            'email' => 'aaa@example.com',
            'desctiption' => str_repeat('a', 255),
            'link' => 'https://google.com' . str_repeat('a', 238)
        ]);

        $expected = ['link' => [
            'maxLength' => 'リンクは255文字までです。'
        ]];
        $this->assertSame($expected, $user->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $user = $this->Users->newEntity([
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => 'password1'
        ]);
        $this->Users->save($user);
        $expected = [
            'username' => [
                '_isUnique' => 'このユーザー名はすでに使われています。'
            ],
            'email' => [
                '_isUnique' => 'このメールアドレスはすでに使われています。'
            ],
        ];
        $this->assertSame($expected, $user->getErrors());
    }
}
