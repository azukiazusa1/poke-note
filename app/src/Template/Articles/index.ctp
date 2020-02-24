<div class="container">
    <div class="row">
        <div class="col s3 hide-on-med-and-down">
            <div class="collection">
                <a href="#!" class="collection-item"><i class="material-icons tiny">trending_up</i> トレンド</a>
                <a href="#!" class="collection-item"><i class="material-icons tiny">people</i> タイムライン</a>
                <a href="#!" class="collection-item"><i class="material-icons tiny">done</i> 最新</a>
                <a href="#!" class="collection-item">Alvin</a>
            </div>
        </div>
        <div class="col s12 m6">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h4 class="light-blue-text accent-1">
                        <i class="material-icons">trending_up</i> トレンド
                    </h4>
                </li>
                <?php foreach ($articles as $article): ?>
                    <li class="collection-item avatar">
                    <?= $this->Html->image($article->user->image, [
                        'alt' => 'Author',
                        'class' => 'circle responsive-img',
                        'url' => ['controller' => 'Users', 'action' => 'show']
                    ])?>
                    <?= $this->Html->link($article->title, 
                        ['controller' => 'articles', 'action' => 'show', $article->id],
                        ['class' => 'title']
                    )?>
                    <p><br>
                        <span class="grey-text darken-1">
                            <i class="tiny material-icons">account_circle</i> 
                            <?= h($article->user->username) ?>
                        </span>
                        <span class="grey-text darken-1">
                            <i class="tiny material-icons">date_range</i>
                            <?= h($article->created->format('Y/m/d')) ?>
                        </span>
                        <span class="grey-text darken-1"> <i class="tiny material-icons red-text accent-2">thumb_up</i> 10</span>
                    </p>
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