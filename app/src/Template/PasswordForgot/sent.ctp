<?php $this->assign('title', 'パスワードリセットメール送信 | PNote!') ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <h1>パスワードリセットメールを送信しました。</h1>
                    </span>
                    <div>
                        <span class="bold"><?= h($email) ?></span>へパスワードリセットメールを送信しました。
                    </div>
                    <div>
                    しばらくしてもメールが届かない場合は、スパムフォルダをご確認ください。
                    </div>
                </div>
                <div class="card-action">
                    <?= $this->Html->Link('トップへ戻る', ['controller' => 'Articles', 'action' => 'index']) ?>
                </div>
            </div>
        </div>
    </div>
</div>