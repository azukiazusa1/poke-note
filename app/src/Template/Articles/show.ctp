<div class="container">
    <div class="row">
        <div class="col m1 hide-on-small-only">
            <ul>
                <li><a class="btn-floating btn-large red accent-2 z-depth-3"><i class="material-icons">thumb_up</i></a></li>
                <li><a class="btn-floating btn-large z-depth-3"><i class="fab fa-twitter blue"></i></a></li>
                <li><a class="btn-floating btn-large red z-depth-3"><i class="material-icons">add</i></a></li>
                <li><a class="btn-floating btn-large red z-depth-3"><i class="material-icons">add</i></a></li>
        </div>
        <div class="col s12 m11">
        <div class="card">
            <div class="card-content">
                <span><?= $this->Html->image(h($article->user->image), [
                    'alt' => 'user',
                    'class' => 'responsive-img circle icon-image',
                ])?></span>
                <span>　@<?= h($article->user->username) ?></span>
                <span class="grey-text darken-1">　<i class="tiny material-icons">date_range</i>
                <?= h($article->created->format('Y/m/d')) ?></span>
                <hr class="list-divider">
                <?php if (count($article->tags) > 0): ?>
                    <?php foreach($article->tags as $tag): ?>
                        <div class="chip">
                            <?= $this->Html->link(h($tag->title), [
                                'controller' => 'tags', 'action' => 'search', h($tag->title)
                            ])?>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <span>この記事にタグはありません。</span>
                <?php endif ?>
                <hr class="list-divider">
                <span class="card-title"><h1><?= h($article->title) ?></h1></span>
                <hr class="list-divider">
                <div id="content"></div>
            </div>
        </div>
        </div>
    </div>

        <div id="content"></div>
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script>
        const content = `<?= h($article->body) ?>`;
        document.getElementById('content').innerHTML =
            marked(content);
        </script>
    </div>
</div>

<style>
    .btn-floating{
        margin: 5px;
    }
</style>