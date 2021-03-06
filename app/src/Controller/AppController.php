<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\Routing\Router;
use Token\Util\Token;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'Login'
            ],
            'loginRedirect' => [
                'controller' => 'Articles',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Articles',
                'action' => 'index'
            ],
            'UnauthorizedRedirect' => [
                'controller' => 'Login',
                'action' => 'index'
            ],
            'authError' => 'ログインが必要です'
        ]);

        $this->loadComponent('LoginCookie');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
        // セッションなし、クッキーあり
        if (!$this->Auth->user('id') && $this->LoginCookie->get()) {
            $token = $this->LoginCookie->get();
            $user = $this->Users->get(Token::getId($token));
            $this->Auth->setUser($user->toArray());
        }
        if ($this->Auth->user('id')) {
            $login_user = $this->Users->get($this->Auth->user('id'));
            $this->set(compact('login_user'));
        }
    }

    // public function beforeRedirect(Event $event, $url, Response $response)
    // {
    //     // httpsにリダイレクトするように設定
    //     $response = $response->withLocation('https://' . $_SERVER["HTTP_HOST"] . Router::url($url));
    //     return $response;
    // }
}
