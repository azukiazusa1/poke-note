<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public $fixtures = ['app.Articles', 'app.Users', 'app.Tags', 'app.ArticlesTags'];

    public function testIndex()
    {
        $this->get('/');

        $this->assertResponseOk();
        $this->assertResponseContains('トレンド');
    }
}