<span class="right">
    <?= $this->Form->postLink('削除<i class="material-icons left">delete</i>', 
        ['controller' => 'Articles', 'action' => 'delete', $id],
        ['escape' => false, 'class' => 'btn-flat white red-text btn-small hide-on-small-only',
        'confirm' => 'この記事を削除します。本当によろしいですか？']
    )?>
    <?= $this->Html->link('編集する<i class="material-icons left">create</i>', 
        ['controller' => 'Articles', 'action' => 'edit', $id],
        ['escape' => false, 'class' => 'btn red accent-2 btn-small hide-on-small-only']
    )?>
</span>

<i class='dropdown-trigger-article material-icons right hide-on-med-and-up' data-target='dropdown-article-<?= $id ?>'>dehaze</i>

<ul id='dropdown-article-<?= $id ?>' class='dropdown-content'>
    <li>
        <?= $this->Html->link('<i class="material-icons tiny">create</i>編集', 
            ['controller' => 'Articles', 'action' => 'edit', $id],
            ['escape' => false, 'class' => 'black-text']
        )?>
    </li>
    <li>
        <?= $this->Form->postLink('<i class="material-icons tiny">delete</i>削除', 
            ['controller' => 'Articles', 'action' => 'delete', $id],
            ['escape' => false, 'class' => 'white red-text',
            'confirm' => 'この記事を削除します。本当によろしいですか？']
        )?>
    </li>
</ul>