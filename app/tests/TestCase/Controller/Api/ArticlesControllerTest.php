<?php
namespace App\Test\TestCase\Api\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ApiArticlesController Test Case
 *
 * @uses \App\Controller\ApiArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public $fixtures = [
        'app.Articles', 
        'app.Users', 
    ];

    public function setUp()
    {
        parent::setUp();
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
    }

    public function test記事の編集ができる()
    {
        $data = [
            'title' => '新しいタイトル',
            'body' => '新しい本文'
        ];
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->put('/api/articles/1.json', $data);

        $this->assertResponseOk();

        $created_date = new \Datetime('2020-02-23 16:22:35');
        $modified_date = new \Datetime();
        $expected = [
            'message' => 'Saved',
            'article' => [
                "id" => 1,
                "user_id" => 1,
                "title" => '新しいタイトル',
                "body" => '新しい本文',
                "published" => true,
                "created" => $created_date->format(\DateTime::ATOM),
                "modified" => $modified_date->format(\DateTime::ATOM),
                "comment_count" => 0,
                "favorite_count" => 0,
                "formated_created" => $created_date->format('Y/m/d H:i:s')
            ],
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());
    }

    public function test存在しない記事()
    {
        $data = [
            'title' => '新しいタイトル',
            'body' => '新しい本文'
        ];
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->put('/api/articles/999.json', $data);

        $this->assertResponseCode(404);

        $expected = [
            'message' => '記事が存在しません。',
            'url' => '/api/articles/999.json',
            'code' => 404,
            'file' => '/var/www/html/app/src/Controller/Api/ArticlesController.php',
            'line' => 45
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());
    }

    public function test他人の記事()
    {
        $data = [
            'title' => '新しいタイトル',
            'body' => '新しい本文'
        ];
        $this->session(['Auth.User.id' => 2]);
        $this->enableCsrfToken();
        $this->put('/api/articles/1.json', $data);

        $this->assertResponseCode(401);

        $expected = [
            'message' => '記事を更新する権限がありません。',
            'url' => '/api/articles/1.json',
            'code' => 401,
            'file' => '/var/www/html/app/src/Controller/Api/ArticlesController.php',
            'line' => 49
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());
    }

    public function test記事更新失敗()
    {
        $data = [
            'title' => str_repeat('a', 256),
            'body' => '新しい本文'
        ];
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->put('/api/articles/1.json', $data);

        $this->assertResponseCode(500);

        $expected = [
            'message' => '予期せぬエラーが発生しました。',
            'url' => '/api/articles/1.json',
            'code' => 500,
            'file' => '/var/www/html/app/src/Controller/Api/ArticlesController.php',
            'line' => 54
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());
    }
}
