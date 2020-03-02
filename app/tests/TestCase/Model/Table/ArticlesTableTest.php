<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArticlesTable Test Case
 */
class ArticlesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArticlesTable
     */
    public $Articles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Articles',
        'app.Users',
        'app.Comments',
        'app.Favorites',
        'app.Tags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Articles') ? [] : ['className' => ArticlesTable::class];
        $this->Articles = TableRegistry::getTableLocator()->get('Articles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Articles);

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
        $article = $this->Articles->newEntity([
            'title' => str_repeat('a', 255),
            'body' => str_repeat('b', 255),
            'published' => 0,
        ]);

        // タイトルと本文を空を許容する
        $article = $this->Articles->newEntity([
            'title' => '',
            'body' => '',
            'published' => 0
        ]);
        $expected = [];
        $this->assertSame($expected, $article->getErrors());

        // タイトルは255文字まで
        $article = $this->Articles->newEntity([
            'title' => str_repeat('a', 256),
            'body' => str_repeat('a', 256),
            'published' => 0
        ]);

        // publishedは必須項目
        $article = $this->Articles->newEntity([
            'published' => ''
        ]);
        $expected = [
            'published' => ['_empty' => '下書きか公開かは必ず指定する必要があります。'],
        ];
        $this->assertSame($expected, $article->getErrors());

         // publishedは真偽値のみ
        $article = $this->Articles->newEntity([
            'published' => 'aaa'
        ]);
        $expected = [
            'published' => ['boolean' => 'The provided value is invalid'],
        ];

        // 公開時にはタイトルと本文は必須
        $article = $this->Articles->newEntity([
            'title' => '',
            'body' => '',
            'published' => 1
        ]);

        $expected = [
            'title' => ['_empty' => '記事を公開する場合にはタイトルは必須項目です。'],
            'body' => ['_empty' => '記事を公開する場合には本文は必須項目です。'],
        ];
        $this->assertSame($expected, $article->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $article = $this->Articles->newEntity([
            'user_id' => 1,
            'published' => 0
        ]);
        $article = $this->Articles->save($article);
        $this->assertFalse($article->hasErrors());

        $article = $this->Articles->newEntity([
            'user_id' => 999,
            'published' => 0
        ]);
        $this->assertFalse($this->Articles->save($article));
    }
}
