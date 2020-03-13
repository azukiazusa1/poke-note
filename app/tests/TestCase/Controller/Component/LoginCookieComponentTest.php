<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\LoginCookieComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\LoginCookieComponent Test Case
 */
class LoginCookieComponentTest extends TestCase
{
    public $fixtures = [
        'app.Users', 
    ];
    /**
     * Test subject
     *
     * @var \App\Controller\Component\LoginCookieComponent
     */
    public $LoginCookie;
    public $controller;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $request = new ServerRequest();
        $response = new Response();
        $this->controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->setConstructorArgs([$request, $response])
            ->setMethods(null)
            ->getMock();
        $registry = new ComponentRegistry($this->controller);
        $this->LoginCookie = new LoginCookieComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LoginCookie, $this->controller);

        parent::tearDown();
    }

    /**
     * Test generate method
     *
     * @return void
     */
    public function testGenerate()
    {
        $user = TableRegistry::getTableLocator()->get('Users')->get(1);
        $this->assertTrue($this->LoginCookie->generate($user));
    }

    /**
     * Test get method
     *
     * @return void
     */
    public function testGet()
    {
        $this->assertEmpty($this->LoginCookie->get());
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->assertTrue($this->LoginCookie->delete());
    }
}
