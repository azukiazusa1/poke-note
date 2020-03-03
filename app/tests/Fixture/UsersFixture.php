<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'username' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nickname' => ['type' => 'string', 'length' => 32, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'link' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'article_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => 0, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'follow_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => 0, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'follower_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => 0, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'username' => ['type' => 'unique', 'columns' => ['username'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'username' => 'user1',
                'password' => 'user1',
                'nickname' => 'user1',
                'email' => 'user1',
                'created' => '2020-02-23 16:23:10',
                'modified' => '2020-02-23 16:23:10',
                'article_count' => 3,
                'follow_count' => 1,
                'follower_count' => 2
            ],
            [
                'id' => 2,
                'username' => 'user2',
                'password' => 'user2',
                'nickname' => 'user2',
                'email' => 'user2',
                'created' => '2020-02-23 16:23:10',
                'modified' => '2020-02-23 16:23:10',
                'article_count' => 2,
                'follow_count' => 1,
                'follower_count' => 1
            ],
            [
                'id' => 3,
                'username' => 'b',
                'password' => 'user3',
                'nickname' => 'user3',
                'email' => 'user3',
                'created' => '2020-02-23 16:23:10',
                'modified' => '2020-02-23 16:23:10',
                'article_count' => 1,
                'follow_count' => 1,
                'follower_count' => 0
            ],
        ];
        parent::init();
    }
}
