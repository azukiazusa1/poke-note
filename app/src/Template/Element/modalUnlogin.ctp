<div id="modal-unlogin" class="modal">
    <div class="modal-content">
      <h4><?= h($do) ?>をするためには？</h4>
	  <p>この機能を利用するためには<?= $this->Html->link('ユーザー登録',['controller' => 'Users', 'action' => 'signup'])?>が必要です。</p>
	  <p>すでに登録済の方はこちらから<?= $this->Html->link('ログイン', ['controller' => 'Users', 'action' => 'login'])?></p>
	</div>
</div>