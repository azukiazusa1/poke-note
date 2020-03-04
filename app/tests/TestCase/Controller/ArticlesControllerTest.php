<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use App\Model\Entity\Article;

class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public $fixtures = [
        'app.Articles', 
        'app.Users', 
        'app.Tags', 
        'app.ArticlesTags', 
        'app.Follows',
        'app.Comments',
        'app.Favorites'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->Articles = TableRegistry::getTableLocator()->get('Articles');
    }

    public function test記事一覧トレンド画面が表示される()
    {
        $this->get('/');

        $this->assertResponseOk();
        $this->assertResponseContains('<i class="material-icons">trending_up</i>トレンド');

        $actual = $this->viewVariable('articles');
        $sampleArticle = $actual->sample(1)->first();

        $this->assertInstanceOf(Article::class,$sampleArticle);
    }

    public function test記事一覧最新画面が表示される()
    {
        $this->get('/latest');

        $this->assertResponseOk();
        $this->assertResponseContains('<i class="material-icons">done</i>最新');

        $actual = $this->viewVariable('articles');
        $sampleArticle = $actual->sample(1)->first();

        $this->assertInstanceOf(Article::class,$sampleArticle);
    }

    public function testタイムラインはログインが必要()
    {
        $this->get('/timeline');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Ftimeline');
    }

    public function test記事一覧タイムライン()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/timeline');

        $this->assertResponseOk();
        $this->assertResponseContains('<i class="material-icons">people</i> タイムライン');

        $actual = $this->viewVariable('articles');
        $sampleArticle = $actual->sample(1)->first();

        $this->assertInstanceOf(Article::class,$sampleArticle);
    }

    public function test新規投稿画面はログインが必要()
    {
        $this->get('/articles/new');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Farticles%2Fnew');
    }

    public function test新規投稿画面()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/new');

        $this->assertResponseCode(302);
        $this->assertRedirect(['controller' => 'Articles',  'action' => 'edit', 7]);
    }

    public function test記事編集画面はログインが必要()
    {
        $this->get('/articles/edit/1');

        $this->assertResponseCode(302);
        $this->assertRedirect('/login?redirect=%2Farticles%2Fedit%2F1');
    }

    public function test異なるユーザーの記事を編集しようとしたとき()
    {
        $this->session(['Auth.User.id' => 3]);
        $this->get('/articles/edit/1');

        $this->assertResponseCode(403);
    }

    public function test記事編集画面()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/edit/1');

        $this->assertResponseok();
    }

    public function test存在しない記事の編集画面()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/edit/999');

        $this->assertResponseCode(404);

    }

    public function test記事編集()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();

        $data = [
            'published' => 1,
            'title' => 'テスト投稿',
            'body' => 'lorem ipsum',
        ];
        $this->put('/articles/edit/1', $data);

        $query = $this->Articles->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());
        $this->assertResponseSuccess();
        $this->assertFlashMessage('更新に成功しました。');
        $this->assertFlashElement('Flash/success');
    }

    public function test記事編集失敗()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->enableCsrfToken();

        $data = [
            'published' => 1,
            'title' => '',
            'body' => '',
        ];
        $this->put('/articles/edit/1', $data);

        $query = $this->Articles->find()->where(['title' => $data['title']]);
        $this->assertEquals(0, $query->count());
        $this->assertResponseSuccess();
        $this->assertResponseContains('記事を公開する場合にはタイトルは必須項目です。');
        $this->assertResponseContains('記事を公開する場合には本文は必須項目です。');
    }

    public function test記事詳細()
    {
        $this->get('/articles/show/1');
        $this->assertResponseOk();

        $article = $this->viewVariable('article');

        $this->assertInstanceOf(Article::class,$article);
    }

    public function test存在しない記事詳細()
    {
        $this->get('/articles/show/999');
        $this->assertResponseCode(404);
    }

    public function test記事詳細下書きの場合他のユーザーは404()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/show/6');
        $this->assertResponseCode(404);
    }

    public function test記事詳細下書きの場合ユーザーが一致するなら閲覧可能()
    {
        $this->session(['Auth.User.id' => 2]);
        $this->get('/articles/show/6');
        $this->assertResponseOk();

        $this->assertResponseContains('この記事はまだ公開されていません。');
    }

    public function test記事削除成功()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/delete/1');
        $this->enableCsrfToken();

        $this->post('/articles/delete/1');
        

        $article = $this->Articles->find()->where(['id' => 1])->first();
        $this->assertEmpty($article);
        $this->assertFlashMessage('記事を削除しました。');
        $this->assertFlashElement('Flash/success');

    }

    public function 他人の記事は削除できない()
    {
        $this->session(['Auth.User.id' => 2]);

        $this->post('/articles/delete/1');
        $this->assertResponseCode(403);
    }

    public function 存在しない記事を削除しようとしたとき()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/delete/999');

        $this->assertResponseCode(404);
    }

    public function test検索画面()
    {
        $this->get('/search/?q=first');
        $this->assertResponseok();

        $articles = $this->viewVariable('articles');
        $article = $articles->sample(1)->first();
        $this->assertInstanceOf(Article::class,$article);
        $this->assertContains('first', $article->title);
    }
}