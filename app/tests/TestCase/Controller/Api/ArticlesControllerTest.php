<?php
namespace App\Test\TestCase\Api\Controller;

use App\Controller\ArticlesController;
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
        // 'app.Tags', 
        // 'app.ArticlesTags', 
        // 'app.Follows',
        // 'app.Comments',
        // 'app.Favorites'
    ];

    public function test記事の編集ができる()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);

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
}
