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
    public function testコメントが投稿できる()
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

    public function testコメント投稿失敗()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->enableCsrfToken();

        $data = [
            'article_id' => 1,
            'body' => '',
        ];
        $this->post('/comments/add/', $data);

        $this->assertFlashMessage('コメントの投稿に失敗しました。');
        $this->assertFlashElement('Flash/error');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testコメントの編集ができる()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();

        $data = [
            'body' => 'コメントを編集しました。',
        ];
        $this->put('/comments/edit/1', $data);

        $comment = $this->Comments->get(1);
        $this->assertSame($data['body'], $comment->body);

        $this->assertFlashMessage('コメントを編集しました。');
        $this->assertFlashElement('Flash/success');
    }

    public function testコメントの編集失敗()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();

        $data = [
            'body' => '',
        ];
        $this->put('/comments/edit/1', $data);

        $this->assertFlashMessage('コメントの編集に失敗しました。');
        $this->assertFlashElement('Flash/error');
    }

    public function test存在しないコメントの編集()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();

        $data = [
            'body' => 'コメントを編集しました。',
        ];
        $this->put('/comments/edit/999', $data);

        $this->assertResponseCode(404);
    }

    public function test別のユーザーのコメントの編集はできない()
    {
        $this->session(['Auth.User.id' => 2]);
        $this->enableCsrfToken();

        $data = [
            'body' => 'コメントを編集しました。',
        ];
        $this->put('/comments/edit/1', $data);

        $this->assertResponseCode(403);
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
