<?php
namespace App\Test\TestCase\Controller;

use App\Controller\Api\Articles\FavoritesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api/Articles/FavoritesController Test Case
 *
 * @uses \App\Controller\Api/Articles/FavoritesController
 */
class FavoritesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public $fixtures = [
        'app.Articles', 
        'app.Users', 
        'app.Favorites'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
    }

    public function tearDown()
    {
        unset($this->Favorites);
    }

    public function test記事にいいねをすることができる()
    {
        $this->session(['Auth.User.id' => 2]);
        $this->enableCsrfToken();
        $this->post('/api/articles/1/favorites.json');

        $this->assertResponseOk();

        $expected = [
            'message' => 'Saved',
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());

        $favorite = $this->Favorites->find()
            ->where([
                'article_id' => 1,
                'user_id' => 2
            ])
            ->first();
        
        $this->assertNotEmpty($favorite);
    }

    public function testいいねした記事を取り消すことができる()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->post('/api/articles/3/favorites.json');

        $this->assertResponseOk();

        $expected = [
            'message' => 'Deleted',
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());

        $favorite = $this->Favorites->find()
            ->where([
                'article_id' => 1,
                'user_id' => 2
            ])
            ->first();
        
        $this->assertEmpty($favorite);
    }

    public function test自分の記事にもいいねをすることができる()
    {

        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();
        $this->post('/api/articles/1/favorites.json');

        $this->assertResponseOk();

        $expected = [
            'message' => 'Saved',
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, (string)$this->_response->getBody());

        $favorite = $this->Favorites->find()
            ->where([
                'article_id' => 1,
                'user_id' => 1
            ])
            ->first();
        
        $this->assertNotEmpty($favorite);
    }
}
