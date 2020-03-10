<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\CommentsController Test Case
 *
 * @uses \App\Controller\CommentsController
 */
class CommentsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Comments',
        'app.Articles',
        'app.Users',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->Comments = TableRegistry::getTableLocator()->get('Comments');
    }


    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();

        $data = [
            'article_id' => 1,
            'body' => 'lorem ipsum',
        ];
        $this->post('/comments/add/', $data);

        $comments = $this->Comments->find('byUserId', ['user_id' => 4]);

        $this->assertSame(1, $comments->count());
        $this->assertResponseSuccess();
        $this->assertFlashMessage('コメントを投稿しました。');
        $this->assertFlashElement('Flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
