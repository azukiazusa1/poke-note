<?php $this->assign('title', 'パスワードの再設定 | PNote!') ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <h1>パスワードの再設定</h1>
                    </span>
                    <div>
                       新しく設定するパスワードを入力してください。
                    </div>
                    <?= $this->Form->create($user) ?>
                    <?= $this->Form->control('password', ['label' => 'パスワードは6文字以上20文字以下で、英数字をそれぞれ1文字以上含める必要があります。',
                        'value' => '', 'class' => 'validate']) ?>
                </div>
                <div class="card-action">
                    <?= $this->Form->button('送信', ['class' => 'btn blue btn-large']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>