<h3>ユーザー登録</h3>
<?= $this->Form->create() ?>
<?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 50, 'class'=>'validate']) ?>
<?= $this->Form->control('email', ['label' => 'メールアドレス', 'maxLength' => 255, 'class'=>'validate']) ?>
<?= $this->Form->control('password', ['label' => 'パスワード', 'maxLength' => 50, 'class'=>'validate', 'id' => 'icon_prefix']) ?>

<?= $this->Form->button('ユーザー登録', ['class' => 'btn blue btn-large']) ?>
<?= $this->Form->end() ?>
