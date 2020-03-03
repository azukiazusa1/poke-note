<div class="container">
    <div class="row">
        <div class="col s3 hide-on-med-and-down">
            <div class="collection">
                <?= $this->Html->link('<i class="material-icons tiny">trending_up</i>トレンド',
                    ['controller' => 'Articles', 'action' => 'index'],
                    ['class' => 'collection-item', 'escape' => false]
                )?>
                <?= $this->Html->link('<i class="material-icons tiny">done</i>最新',
                    ['controller' => 'Articles', 'action' => 'latest'],
                    ['class' => 'collection-item', 'escape' => false]
                )?>
                <?php if (isset($login_user)): ?>
                    <?= $this->Html->link('<i class="material-icons tiny">people</i>タイムライン',
                        ['controller' => 'Articles', 'action' => 'timeline'],
                        ['class' => 'collection-item', 'escape' => false]
                    )?>
                    <a href="#!" class="collection-item"><i class="material-icons tiny">local_offer</i>タグ</a>
                <? endif ?>
            </div>
        </div>
        <div class="col s12 m6">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h4 class="light-blue-text accent-1">
                        <?php switch ($this->request->getParam('action')):
                        case 'index': ?>
                            <i class="material-icons">trending_up</i>トレンド
                        <?php break; ?>
                        <?php case 'latest': ?>
                            <i class="material-icons">done</i>最新
                        <?php break; ?>
                        <?php case 'timeline': ?>
                            <i class="material-icons">people</i> タイムライン
                        <?php break; ?>
                        <?php case 'tag': ?>
                            <i class="material-icons">local_offer</i>タグ
                        <?php break; ?>
                        <?php default:
                                break;
                        endswitch ?>
                    </h4>
                </li>
                <?php foreach ($articles as $article): ?>
                    <li class="collection-item avatar">
                        <div>
                            <?= $this->Html->image(h($article->user->image), [
                                'alt' => 'Author',
                                'class' => 'circle responsive-img',
                                'url' => ['controller' => 'Users', 'action' => 'show', h($article->user->username)]
                            ])?>
                            <?= $this->Html->link(h($article->title), 
                                ['controller' => 'articles', 'action' => 'show', $article->id],
                                ['class' => 'title']
                            )?>
                        </div>
                        <div class="grey-text TagList">
                            <i class="tiny material-icons grey-text">local_offer</i>
                            <?php foreach ($article->tags as $tag): ?>
                                <span class="tag">
                                    <?= $this->Html->link(h($tag->title), 
                                        ['controller' => 'Tags', 'action' => 'show', h($tag->title)],
                                        ['class' => 'tag-color']
                                    ) ?>
                                </span>
                            <?php endforeach ?>
                        </div>
                        <div>
                            <span>
                                <?= $this->Html->link('@' . h($article->user->username), 
                                    ['controller' => 'Users', 'action' => 'show', $article->user->username]
                                )?>
                            </span>
                            <span class="grey-text">
                                <i class="tiny material-icons red-text text-accent-2">thumb_up</i>
                                <?= h($article->favorite_count) ?>
                            </span>
                            <span class="grey-text">
                                <i class="tiny material-icons teal-text text-lighten-2">comment</i>
                                <?= h($article->comment_count) ?>
                            </span>
                            <span class="grey-text darken-1 hide-on-small-only">
                                <i class="tiny material-icons">date_range</i>
                                <?= h($article->created->format('Y/m/d H:i:s')) ?>
                            </span>
                            <p class="grey-text darken-1 hide-on-med-and-up">
                                <i class="tiny material-icons">date_range</i>
                                <?= h($article->created->format('Y/m/d H:i:s')) ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="col s3 hide-on-med-and-down">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Card Title</span>
                    <p>I am a very simple card. I am good at containing small bits of information.
                    I am convenient because I require little markup to use effectively.</p>
                </div>
                <div class="card-action">
                    <a href="#">This is a link</a>
                    <a href="#">This is a link</a>
                </div>
            </div>
        </div>
    </div>
</div>