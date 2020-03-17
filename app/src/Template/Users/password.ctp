<?php $this->assign('title', 'パスワード | PNote!') ?>
<div class="container">
    <div class="row">
        <div class="col m3 s12">
            <div class="collection">
                <?= $this->Html->link('プロフィール編集', ['controller' => 'Users', 'action' => 'edit'],
                    ['class' => 'collection-item black-text']) ?>
                <?= $this->Html->link('メールアドレス', ['controller' => 'Users', 'action' => 'email'],
                    ['class' => 'collection-item black-text']) ?>
                <?= $this->Html->link('パスワード', ['controller' => 'Users', 'action' => 'password'],
                    ['class' => 'collection-item red accent-2 white-text']) ?>
                <?= $this->Html->link('アカウントを削除', ['controller' => 'Users', 'action' => 'delete'],
                    ['class' => 'collection-item black-text']) ?>
            </div>
		</div>
		<div class="col m9 s12">
			<div class="card">
				<div class="card-content">
					<span class="card-title"><h1>パスワード / @<?= h($user->username) ?></p></h1></span>
					<?= $this->Form->create($user) ?>
					<?= $this->Form->control('old_password', ['label' => '現在のパスワード', 'maxlength' => 32, 'type' => 'password', 'value' => '']) ?>
					<?= $this->Form->control('password', ['label' => '新しいパスワード', 'maxlength' => 32, 'value' => '']) ?>
					<?= $this->Form->button('更新する', ['class' => 'btn blue btn-large']) ?>
					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>
</div>