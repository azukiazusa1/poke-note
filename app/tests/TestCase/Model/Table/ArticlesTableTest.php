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
        'app.ArticlesTags',
        'app.Follows'
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

    /**
     * 公開(published = 1)のみ
     *
     * @return void
     */
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

    /**
     * 公開中 いいねが多い順
     *
     * @return void
     */
    public function testFindTrend()
    {
        $query = $this->Articles->find('trend');
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
            ],
            [
                'id' => 4,
                'user_id' => 3,
                'title' => 'first title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-05-23 16:22:35'),
                'modified' => new FrozenTime('2020-05-23 16:22:35'),
                'comment_count' => 3,
                'favorite_count' => 3,
                'tags' => [],
                'user' => [
                    'id' => 3,
                    'username' => 'b',
                    'password' => 'user3',
                    'nickname' => 'user3',
                    'email' => 'user3',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 1,
                    'follow_count' => 1,
                    'follower_count' => 0
                ],
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'second title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-03-23 16:22:35'),
                'modified' => new FrozenTime('2020-03-23 16:22:35'),
                'comment_count' => 2,
                'favorite_count' => 1,
                'tags' => [],
                'user' => [
                    'id' => 1,
                    'username' => 'user1',
                    'password' => 'user1',
                    'nickname' => 'user1',
                    'email' => 'user1',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 3,
                    'follow_count' => 1,
                    'follower_count' => 2
                ],
            ],
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'first title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-02-23 16:22:35'),
                'modified' => new FrozenTime('2020-02-23 16:22:35'),
                'comment_count' => 0,
                'favorite_count' => 0,
                'tags' => [
                    0 => [
                        'id' => 1,
                        'title' => 'タグ1',
                        'created' => new FrozenTime('2020-02-23 16:23:05'),
                        'modified' => new FrozenTime('2020-02-23 16:23:05'),
                        '_joinData' => [
                            'article_id' => 1,
                            'tag_id' => 1
                        ],
                    ],
                ],
                'user' => [
                    'id' => 1,
                    'username' => 'user1',
                    'password' => 'user1',
                    'nickname' => 'user1',
                    'email' => 'user1',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 3,
                    'follow_count' => 1,
                    'follower_count' => 2
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * 公開中 最新順
     *
     * @return void
     */
    public function testFindLatest()
    {
        $query = $this->Articles->find('latest');
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 4,
                'user_id' => 3,
                'title' => 'first title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-05-23 16:22:35'),
                'modified' => new FrozenTime('2020-05-23 16:22:35'),
                'comment_count' => 3,
                'favorite_count' => 3,
                'tags' => [],
                'user' => [
                    'id' => 3,
                    'username' => 'b',
                    'password' => 'user3',
                    'nickname' => 'user3',
                    'email' => 'user3',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 1,
                    'follow_count' => 1,
                    'follower_count' => 0
                ],
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'second title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-03-23 16:22:35'),
                'modified' => new FrozenTime('2020-03-23 16:22:35'),
                'comment_count' => 2,
                'favorite_count' => 1,
                'tags' => [],
                'user' => [
                    'id' => 1,
                    'username' => 'user1',
                    'password' => 'user1',
                    'nickname' => 'user1',
                    'email' => 'user1',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 3,
                    'follow_count' => 1,
                    'follower_count' => 2
                ],
            ],
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'first title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-02-23 16:22:35'),
                'modified' => new FrozenTime('2020-02-23 16:22:35'),
                'comment_count' => 0,
                'favorite_count' => 0,
                'tags' => [
                    0 => [
                        'id' => 1,
                        'title' => 'タグ1',
                        'created' => new FrozenTime('2020-02-23 16:23:05'),
                        'modified' => new FrozenTime('2020-02-23 16:23:05'),
                        '_joinData' => [
                            'article_id' => 1,
                            'tag_id' => 1
                        ],
                    ],
                ],
                'user' => [
                    'id' => 1,
                    'username' => 'user1',
                    'password' => 'user1',
                    'nickname' => 'user1',
                    'email' => 'user1',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 3,
                    'follow_count' => 1,
                    'follower_count' => 2
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * ユーザーがフォローしているユーザーの物件
     *
     * @return void
     */
    public function testfindTimeline()
    {
        $query = $this->Articles->find('timeline', ['user_id' => 1]);
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
                '_matchingData' => [
                    'Users' => [
                        'id' => 2,
                        'username' => 'user2',
                        'password' => 'user2',
                        'nickname' => 'user2',
                        'email' => 'user2',
                        'created' => new FrozenTime('2020-02-23 16:23:10'),
                        'modified' => new FrozenTime('2020-02-23 16:23:10'),
                        'article_count' => 2,
                        'follow_count' => 1,
                        'follower_count' => 1
                    ],
                    'Followers' => [
                        'id' => 1,
                        'user_id' => 1,
                        'follow_user_id' => 2,
                        'created' => new FrozenTime('2020-02-29 22:09:42'),
                        'modified' => new FrozenTime('2020-02-29 22:09:42')
                    ]
                ]
        
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * 検索
     *
     * @return void
     */
    public function testfindSearch()
    {
        $query = $this->Articles->find('search', ['q' => 'draft']);
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * 検索
     *
     * @return void
     */
    public function testFindByUserId()
    {
        $query = $this->Articles->find('byUserId', ['user_id' => 2]);
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * 下書き検索
     *
     * @return void
     */
    public function testFindDraftByUserId()
    {
        $query = $this->Articles->find('draftByUserId', ['user_id' => 2]);
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 6,
                'user_id' => 2,
                'title' => 'second title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => false,
                'created' => new FrozenTime('2020-06-23 16:22:35'),
                'modified' => new FrozenTime('2020-06-23 16:22:35'),
                'comment_count' => 0,
                'favorite_count' => 0,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testFindUserFavorite()
    {
        $query = $this->Articles->find('userFavorites', ['user_id' => 1]);
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        debug($result);
        $expected = [
            [
                'id' => 3,
                'user_id' => 2,
                'title' => 'draft title',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'published' => true,
                'created' => new FrozenTime('2020-04-23 16:22:35'),
                'modified' => new FrozenTime('2020-04-23 16:22:35'),
                'comment_count' => 1,
                'favorite_count' => 5,
                'tags' => [],
                'user' => [
                    'id' => 2,
                    'username' => 'user2',
                    'password' => 'user2',
                    'nickname' => 'user2',
                    'email' => 'user2',
                    'created' => new FrozenTime('2020-02-23 16:23:10'),
                    'modified' => new FrozenTime('2020-02-23 16:23:10'),
                    'article_count' => 2,
                    'follow_count' => 1,
                    'follower_count' => 1
                ],
                '_matchingData' => [
                    'Favorites' => [
                        'id' => 1,
                        'article_id' => 3,
                        'user_id' => 1,
                        'created' => new FrozenTime('2020-02-23 16:23:33'),
                        'modified' => new FrozenTime('2020-02-23 16:23:33'),
                    ]
                ]
        
            ]
        ];
        $this->assertEquals($expected, $result);
    }
}
