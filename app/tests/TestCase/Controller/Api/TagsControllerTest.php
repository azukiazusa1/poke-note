<?php
namespace App\Test\TestCase\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api|TagstsController Test Case
 *
 * @uses \App\Controller\Api\TagstsController
 */
class TagsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Tags',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->Tags = TableRegistry::getTableLocator()->get('Tags');
    }

    public function tearDown(): void
    {
        unset($this->Tags);
    }

    public function testタグが追加できる()
    {
        $data = [
            'title' => '新しいタグ'
        ];
        $this->post('/api/tags.json', $data);

        $this->assertResponseOk();

        $data = new  \Datetime();
        $data = $data->format(\DateTime::ATOM);
        $expected = [
            'message' => 'Saved',
            'tag' => [
                "created" => $data,
                "modified" => $data,
                "id" => 4
            ],
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());
    }
}