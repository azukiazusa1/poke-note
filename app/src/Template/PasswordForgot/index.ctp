<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <h3>パスワードをリセットする</h3>
                    </span>
                    <div>
                        ユーザー登録時に設定したパスワードを入力してください。
                    </div>
                    <?= $this->Form->create(null, [
                        'url' => ['controller' => 'PasswordForgot', 'action' => 'sent', 'label' => 'メールアドレス', 'class' => 'validate']
                    ]) ?>
                    <?= $this->Form->control('email') ?>
                </div>
                <div class="card-action">
                    <?= $this->Form->button('送信', ['class' => 'btn blue btn-large']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>