<div class="container">
    <div class="row">
        <div class="col m3 s12">
            <div class="collection">
                <?= $this->Html->link('プロフィール編集', ['controller' => 'Users', 'action' => 'edit'],
                    ['class' => 'collection-item black-text']) ?>
                <?= $this->Html->link('メールアドレス', ['controller' => 'Users', 'action' => 'email'],
                    ['class' => 'collection-item black-text']) ?>
                <?= $this->Html->link('パスワード', ['controller' => 'Users', 'action' => 'password'],
                    ['class' => 'collection-item black-text']) ?>
                <?= $this->Html->link('アカウントを削除', ['controller' => 'Users', 'action' => 'delete'],
                    ['class' => 'collection-item red accent-2 white-text']) ?>
            </div>
        </div>
        <div class="col m9 s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title"><h4 class="red-text">アカウントを削除 / @<?= h($user->username) ?></p></h4></span>
                    <p>一度アカウントを削除されますと、もとに戻すことができません。</p>
                    <p>十分にご注意ください。</p>
                    <?= $this->Form->create() ?>
                    <?= $this->Form->control('password', ['label' => 'パスワード', 'maxlength' => 32, 'value' => '']) ?>
                    <?= $this->Form->button('アカウントを削除', ['class' => 'btn red btn-large', 'confirm' => '本当によろしいですか？']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>