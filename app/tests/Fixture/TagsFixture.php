<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TagsFixture
 */
class TagsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'article_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => 0, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'title' => ['type' => 'unique', 'columns' => ['title'], 'length' => []],
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
                'title' => 'タグ1',
                'created' => '2020-02-23 16:23:05',
                'modified' => '2020-02-23 16:23:05',
                'article_count' => 1
            ],
            [
                'id' => 2,
                'title' => 'タグ2',
                'created' => '2020-02-23 16:23:05',
                'modified' => '2020-02-23 16:23:05',
                'article_count' => 2
            ],
            [
                'id' => 3,
                'title' => 'タグ3',
                'created' => '2020-02-23 16:23:05',
                'modified' => '2020-02-23 16:23:05',
                'article_count' => 3
            ],
        ];
        parent::init();
    }
}
