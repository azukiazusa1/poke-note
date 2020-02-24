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
    <?= $this->Html->css('https://fonts.googleapis.com/icon?family=Material+Icons') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <ul id="dropdown1" class="dropdown-content">
        <li><?= $this->Html->link('マイページ', ['controller' => 'Users', 'action' => 'show'])?></li>
        <li><?= $this->Html->link('設定', ['controller' => 'Users', 'action' => 'edit'])?></li>
        <li><?= $this->Html->link('ログアウト', ['controller' => 'Users', 'action' => 'logout'])?></li>
    </ul>
    <nav class="red accent-2">
        <div class="nav-wrapper">
            <?= $this->Html->link('Logo',['controller' => 'Articles', 'action' => 'index'], ['class' => 'brand-logo'])?>
            <ul class="right hide-on-med-and-down">
                <?php if (isset($login_user)): ?>
                    <li>
                        <?= $this->Html->link('投稿する<i class="material-icons left">create</i>', 
                        ['controller' => 'Articles', 'action' => 'new'],
                        ['escape' => false, 'class' => 'btn red darken-3']
                        )?>
                    </li>
                    <li><a href="collapsible.html"><i class="material-icons">refresh</i></a></li>
                    <li><a href="mobile.html"><i class="material-icons">more_vert</i></a></li>
                    <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">
                        <?= $this->Html->image($login_user->image, [
                            'alt' => 'Author',
                            'class' => 'responsive-img circle icon-image',
                        ])?>
                        <i class="material-icons right">arrow_drop_down</i></a></li>
                <?php else: ?>
                    <li><?= $this->Html->link('ログイン', ['controller' => 'Users', 'action' => 'login'])?></li>
                    <li><?= $this->Html->link('ユーザー登録',['controller' => 'Users', 'action' => 'signup'])?></li>
                <?php endif ?>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
    <footer>
    </footer>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elems = document.querySelectorAll('.dropdown-trigger');
            M.Dropdown.init(elems, {
                coverTrigger: false
            });
        });
    </script>
</body>
</html>
