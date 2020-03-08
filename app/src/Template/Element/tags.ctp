<?php if (count($article->tags) > 0): ?>
    <?php foreach($article->tags as $tag): ?>
        <div class="chip">
            <?= $this->Html->link(h($tag->title), [
                'controller' => 'tags', 'action' => 'show', h($tag->title)
            ])?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <span class="small-text">この記事にタグはありません。</span>
<?php endif ?>