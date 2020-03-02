<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\ORM\Query;
use Cake\I18n\FrozenTime;

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

    public function testFindPublished()
    {
        $query = $this->Articles->find('published');
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'first title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => 1,
                'created' => new FrozenTime('2020-02-23 16:22:35'),
                'modified' => new FrozenTime('2020-02-23 16:22:35'),
                'comment_count' => 0,
                'favorite_count' => 0,
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'second title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => 1,
                'created' => new FrozenTime('2020-03-23 16:22:35'),
                'modified' => new FrozenTime('2020-03-23 16:22:35'),
                'comment_count' => 2,
                'favorite_count' => 1,
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => 1,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
            ],
            [
                'id' => 4,
                'user_id' => 3,
                'title' => 'first title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => 1,
                'created' => new FrozenTime('2020-05-23 16:22:35'),
                'modified' => new FrozenTime('2020-05-23 16:22:35'),
                'comment_count' => 3,
                'favorite_count' => 3,
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    // public function testFindTrend()
    // {
    //     $query = $this->Articles->find('trend');
    //     $this->assertInstanceOf(Query::class, $query);
    //     $result = $query->enableHydration(false)->toArray();
    //     [
    //         'id' => 1,
    //         'user_id' => 1,
    //         'title' => 'first title',
    //         'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
    //         'published' => 1,
    //         'created' => '2020-02-23 16:22:35',
    //         'modified' => '2020-02-23 16:22:35',
    //         'comment_count' => 0,
    //         'favorite_count' => 0,
    //     ],
    //     [
    //         'id' => 2,
    //         'user_id' => 1,
    //         'title' => 'second title',
    //         'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
    //         'published' => 0,
    //         'created' => '2020-03-23 16:22:35',
    //         'modified' => '2020-03-23 16:22:35',
    //         'comment_count' => 2,
    //         'favorite_count' => 1,
    //     ],
    //     [
    //         'id' => 3,
    //         'user_id' => 2,
    //         'title' => 'draft title',
    //         'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
    //         'published' => 0,
    //         'created' => '2020-04-23 16:22:35',
    //         'modified' => '2020-04-23 16:22:35',
    //         'comment_count' => 1,
    //         'favorite_count' => 5,
    //     ],
    //     [
    //         'id' => 4,
    //         'user_id' => 3,
    //         'title' => 'first title',
    //         'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
    //         'published' => 1,
    //         'created' => '2020-05-23 16:22:35',
    //         'modified' => '2020-05-23 16:22:35',
    //         'comment_count' => 3,
    //         'favorite_count' => 3,
    //     ],
    //     [
    //         'id' => 5,
    //         'user_id' => 1,
    //         'title' => 'second title',
    //         'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
    //         'published' => 0,
    //         'created' => '2020-06-23 16:22:35',
    //         'modified' => '2020-06-23 16:22:35',
    //         'comment_count' => 0,
    //         'favorite_count' => 0,
    //     ],
    // }
}
