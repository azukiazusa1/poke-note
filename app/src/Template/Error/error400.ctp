<?php $this->assign('title', 'エラー | PNote!') ?>
<div class="container">
    <div class="row">
        <div class="col s12">
        <h1>ページが見つかりません</h1>
            <p>お探しのページは見つかりませんでした。</p>
            <p>移動または削除された可能性があります。</p>
        </div>
        <div class="col s12">
            <?= $this->Html->link('トップへ戻る', [
                'controller' => 'Articles', 'action' => 'index'
            ]) ?>
        </div>
    </div>
</div>

