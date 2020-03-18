<div class="row">
    <div class="col s12">
        <ul class="collection with-header">
            <li class="collection-header">
                <div class="bold">ユーザーランキング</div>
            </li>
            <?php 
            $i = 0;
            foreach($user_ranking as $user):?>
                <li class="collection-item">
                    <?= h(++$i) ?>
                    <?= $this->Html->image(h($user->image), [
                        'alt' => 'Author',
                        'class' => 'circle responsive-img ra-User_image',
                        'url' => ['controller' => 'Users', 'action' => 'show', h($user->username)]
                    ])?>
                    <?= $this->Html->link(h($user->username), 
                        ['controller' => 'Users', 'action' => 'show', h($user->username)]
                    )?>
                    <span class="right">
                        <i class="tiny material-icons red-text text-accent-2">thumb_up</i>
                        <?= h($user->total_favorite) ?>
                    </span>
                </li>   
            <?php endforeach ?>
        </ul>
        <?= $this->Html->link('もっと見る', 
            ['controller' => 'Users', 'action' => 'index'])
        ?>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <ul class="collection with-header">
            <li class="collection-header">
                <div class="bold">タグランキング</div>
            </li>
            <?php 
                $i = 0;
                foreach($tag_ranking as $tag):?>
                <li class="collection-item">
                    <?= h(++$i) ?>
                    <span class="chip">
                        <?= h($tag->title) ?>
                        <span class="fa-stack fa-lg" style="font-size: 1em;">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-inverse fa-stack-1x"><?= h($tag->article_count) ?></i>
                        </span>
                    </span>
                </li>
            <?php endforeach ?>
        </ul>
        <?= $this->Html->link('もっと見る', 
            ['controller' => 'Tags', 'action' => 'index'])
        ?>
    </div>
</div>

<style>
    .ra-User_image {
        width: 33px!important;
        height: 33px!important;
    }
</style>