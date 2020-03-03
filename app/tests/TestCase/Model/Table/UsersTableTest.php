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
    public function testValidationDefault()
    {
        // エラーが無いとき
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
         // urlの形式じゃない時

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
}
