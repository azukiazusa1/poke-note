<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css') ?>
    <?= $this->Html->css('my-style.css') ?>
    <?= $this->Html->css("https://use.fontawesome.com/releases/v5.6.1/css/all.css") ?>
    <?= $this->Html->css('https://fonts.googleapis.com/icon?family=Material+Icons') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="red accent-2">
        <div class="nav-wrapper">
            <?= $this->Html->link('PNote!',['controller' => 'Articles', 'action' => 'index'], ['class' => 'brand-logo left'])?>
            <ul class="right">
                <li class="hide-on-small-only">
                    <form method="get" action="/search">
                        <div class="input-field">
                            <i class="material-icons prefix white-text">search</i>
                            <input name="q" id="search" type="text" class="validate white" placeholder="キーワード検索">
                        </div>
                    </form>
                </li>
                <li class="hide-on-med-and-up">
                    <a class="dropdown-trigger" href="#!" data-target="dropdown2"><i class="material-icons">search</i></a>
                    <ul id="dropdown2" class="dropdown-content">
                        <form method="get" action="/search">
                            <div class="input-field">
                                <input name="q" id="search-mobile" type="text" class="validate white" placeholder="キーワード検索" value="<?= $q ?? ''?>">
                            </div>
                        </form>
                    </ul>
                </li>
                <?php if (isset($login_user)): ?>
                    <li class="hide-on-small-only">
                        <?= $this->Html->link('投稿する<i class="material-icons left">create</i>', 
                        ['controller' => 'Articles', 'action' => 'new'],
                        ['escape' => false, 'class' => 'btn red darken-3']
                        )?>
                    </li>  
                    <li class="hide-on-med-and-up">
                        <?= $this->Html->link('<i class="material-icons">create</i>', 
                        ['controller' => 'Articles', 'action' => 'new'],
                        ['escape' => false, 'class' => 'red darken-3 btn']
                        )?>
                    </li> 
                    <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">
                        <?= $this->Html->image($login_user->image, [
                            'alt' => 'User',
                            'class' => 'responsive-img circle icon-image',
                        ])?>
                        <i class="material-icons right">arrow_drop_down</i></a></li>
                        <ul id="dropdown1" class="dropdown-content">
                            <li><?= $this->Html->link('マイページ', ['controller' => 'Users', 'action' => 'show', $login_user->username])?></li>
                            <li><?= $this->Html->link('下書き一覧', ['controller' => 'Articles', 'action' => 'draft'])?></li>
                            <li><?= $this->Html->link('設定', ['controller' => 'Users', 'action' => 'edit'])?></li>
                            <li><?= $this->Html->link('ログアウト', ['controller' => 'Users', 'action' => 'logout'])?></li>
                        </ul>
                <?php else: ?>
                    <li><?= $this->Html->link('ログイン', ['controller' => 'Users', 'action' => 'login'])?></li>
                    <li><?= $this->Html->link('ユーザー登録',['controller' => 'Users', 'action' => 'signup'])?></li>
                <?php endif ?>
            </ul>
        </div>
    </nav>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js') ?>
    <?= $this->Flash->render() ?>
    <main>
        <?= $this->fetch('content') ?>
    </main>
    <footer class="page-footer blue-grey darken-3">
        <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">PNote!</h5>
                <p class="grey-text text-lighten-4">ポケモン好きのための、記事投稿サイトです。</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">サイトマップ</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="#!">PNote!とは？</a></li>
                  <li>
                      <?= $this->Html->link('記事一覧',
                      ['controller' => 'Articles','action' => 'search'],
                      ['class' => 'grey-text text-lighten-3']) ?>
                  </li>
                  <li>
                      <?= $this->Html->link('タグ一覧',
                      ['controller' => 'Tags','action' => 'index'],
                      ['class' => 'grey-text text-lighten-3']) ?>
                  </li>
                  <li>
                      <?= $this->Html->link('ユーザー一覧',
                      ['controller' => 'Users','action' => 'index'],
                      ['class' => 'grey-text text-lighten-3']) ?>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2020 Copyright
            </div>
          </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelectorAll('.dropdown-trigger');
            M.Dropdown.init(dropdown, {
                coverTrigger: false, 
                constrainWidth: false,
                closeOnClick: false,
            });
            M.FormSelect.init(document.querySelectorAll('select'));
        });
    </script>
</body>
</html>
