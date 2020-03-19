<?php $this->assign('title', 'ユーザー登録 | PNote!') ?>
<div class="container">
<div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <span class="card-title"><h1>ユーザー登録</h1></span>
            <?= $this->Form->create($user) ?>
            <?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 50, 'class'=>'validate']) ?>
            <?= $this->Form->control('email', ['label' => 'メールアドレス', 'maxLength' => 255, 'class'=>'validate']) ?>
            <?= $this->Form->control('password', ['label' => 'パスワードは6文字以上20文字以下で、英数字をそれぞれ1文字以上含める必要があります。', 'maxLength' => 50, 'class'=>'validate']) ?>
            <div class="card-action">
                <?= $this->Form->button('登録する', ['class' => 'btn blue btn-large']) ?> 
            </div>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>
