<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title"><h3>ログイン</h3></span>
                    <?= $this->Form->create() ?>
                    <?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 50, 'class' => 'validate']) ?>
                    <?= $this->Form->control('password', ['label' => 'パスワード', 'maxLength' => 50, 'class' => 'validate']) ?>
                    <div class="card-action">
                        <?= $this->Form->button('ログイン', ['class' => 'btn blue btn-large']) ?>
                        <?= $this->Html->link('パスワードを忘れた場合', ['controller' => 'PasswordForgot', 'action' => 'index'], ['class' => 'right']) ?>
                    </div>
                <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>