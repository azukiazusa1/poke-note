<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;

/**
 * App\Model\Table\CommentsTable Test Case
 */
class CommentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CommentsTable
     */
    public $Comments;

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

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Comments);

        parent::tearDown();
    }

    public function testユーザー名からコメントが検索できる()
    {
        $query = $this->Comments->find('byUserId', ['user_id' => 1]);
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 1,
                'body' => '素晴らしい記事ですね!',
                'article_id' => 2,
                'user_id' => 1,
                'created' => new FrozenTime('2020-02-23 16:23:24'),
                'modified' => new FrozenTime('2020-02-23 16:23:24'),
                'user' => [
                    'id' => 1,
                    'username' => 'user1',
                    'image' => ' user/default.png',
                ],
                'article' => [
                    'id' => 2,
                    'title' => 'second title',
                    'created' => new FrozenTime('2020-3-23 16:22:35'),
                ]
            ],
            [
                'id' => 2,
                'body' => '参考になりました!',
                'article_id' => 3,
                'user_id' => 1,
                'created' => new FrozenTime('2020-02-23 16:23:24'),
                'modified' => new FrozenTime('2020-02-23 16:23:24'),
                'user' => [
                    'id' => 1,
                    'username' => 'user1',
                    'image' => ' user/default.png',
                ],
                'article' => [
                    'id' => 3,
                    'title' => 'draft title',
                    'created' => new FrozenTime('2020-04-23 16:22:35'),
                ]
            ],
        ];
        $this->assertEquals($expected, $result);
        
    }
}
