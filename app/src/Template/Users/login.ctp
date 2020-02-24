<div class="container">
    <h3>ログイン</h3>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 255, 'class'=>'validate']) ?>
    <?= $this->Form->control('password', ['label' => 'パスワード', 'maxLength' => 50]) ?>
    <?= $this->Form->button('ログイン', ['class' => 'btn blue btn-large']) ?>
    <?= $this->Form->end() ?>
</div>