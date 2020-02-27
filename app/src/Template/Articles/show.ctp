<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<div class="container">
    <div class="hide-on-med-and-down fixed-btn">
            <p>
                <a class="btn-floating btn-large red accent-2 z-depth-3"><i class="material-icons">thumb_up</i></a>
                <span class="bold"><?= h($article->favorite_count) ?></span>
            </p>
            <p>
                <a class="btn-floating btn-large z-depth-3" href="#comment"><i class="material-icons">comment</i></a>
                <span class="bold"><?= h($article->comment_count) ?></span>
            </p>
            <p><a class="btn-floating btn-large z-depth-3"><i class="fab fa-twitter twitter"></i></a></p>
            <p><a class="btn-floating btn-large z-depth-3"><i class="fab fa-facebook-f facebook"></i></a></p>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card">
                <div class="card-content">
                    <span><?= $this->Html->image(h($article->user->image), [
                        'alt' => 'user',
                        'class' => 'responsive-img circle icon-image',
                        'url' => ['controller' => 'Users', 'action' => 'show', h($article->user->username)]
                    ])?></span>
                    <span>
                        <?= $this->Html->link('@' . h($article->user->username), 
                                ['controller' => 'Users', 'action' => 'show', $article->user->username]
                            )?>
                    </span>
                    <span class="grey-text darken-1 hide-on-small-only">　<i class="tiny material-icons">date_range</i>
                    <?= h($article->created->format('Y/m/d H:i:s')) ?></span>
                    <?php if($isAuthor): ?>
                        <?= $this->Form->postLink('削除<i class="material-icons left">delete</i>', 
                            ['controller' => 'Articles', 'action' => 'delete', $article->id],
                            ['escape' => false, 'class' => 'btn-flat white red-text btn-small right hide-on-small-only',
                            'confirm' => 'この記事を削除します。本当によろしいですか？']
                        )?>
                        <?= $this->Html->link('編集する<i class="material-icons left">create</i>', 
                            ['controller' => 'Articles', 'action' => 'edit', $article->id],
                            ['escape' => false, 'class' => 'btn red accent-2 btn-small right hide-on-small-only']
                        )?>
                        <p class="grey-text darken-1 hide-on-med-and-up">　
                            <i class="tiny material-icons">date_range</i>
                            <?= h($article->created->format('Y/m/d')) ?>
                            <i class='dropdown-trigger-article material-icons right' data-target='dropdown-article'>dehaze</i>
                        </p>

                        <ul id='dropdown-article' class='dropdown-content'>
                            <li>
                                <?= $this->Html->link('<i class="material-icons tiny">create</i>編集', 
                                    ['controller' => 'Articles', 'action' => 'edit', $article->id],
                                    ['escape' => false, 'class' => 'black-text']
                                )?>
                            </li>
                            <li>
                                <?= $this->Form->postLink('<i class="material-icons tiny">delete</i>削除', 
                                    ['controller' => 'Articles', 'action' => 'delete', $article->id],
                                    ['escape' => false, 'class' => 'white red-text',
                                    'confirm' => 'この記事を削除します。本当によろしいですか？'],
                                )?>
                            </li>
                        </ul>
                    <?php endif ?>
                    <hr class="list-divider">
                    <span class="card-title"><h1><?= h($article->title) ?></h1></span>
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
                    <br>
                    <div class="hide-on-large-only">
                        <a class="btn-floating red accent-2"><i class="material-icons">thumb_up</i></a>10
                        <a class="btn-floating" href="#comment"><i class="material-icons">comment</i></a><?= h($article->comment_count) ?>
                        <a class="btn-floating"><i class="fab fa-twitter twitter"></i></a>
                        <a class="btn-floating"><i class="fab fa-facebook-f facebook"></i></a>
                    </div>
                    <hr class="list-divider">
                    <input type="hidden" id="body" value="<?= $article->body ?>" />
                    <div id="app">
                        <mavon-editor 
                            language="ja" 
                            v-model="value"
                            :default-open="defaultOpen"
                            :toolbars-flag=false
                            :subfield="subfield"
                            :box-shadow=false
                            :preview-background="previewBackground"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card">
                <div class="card-content">
                    <h5 id="comment"><i class="material-icons Medium">comment</i>コメント一覧</h5>
                    <?php if (count($article->comments) > 0) : ?>
                        <?php foreach ($article->comments as $comment) :?>
                            <div class="row">
                                <div class="col s12">
                                    <div class="card horizontal light-blue lighten-5">
                                        <div class="card-stacked">
                                            <div class="card-content">
                                                <span><?= $this->Html->image(h($comment->user->image), [
                                                    'alt' => 'user',
                                                    'class' => 'responsive-img circle icon-image',
                                                    'url' => ['controller' => 'User', 'action' => 'show', $comment->user->username]
                                                ])?></span>
                                                <span>
                                                    <?= $this->Html->link('@' . h($comment->user->username), 
                                                        ['controller' => 'Users', 'action' => 'show', $comment->user->username],
                                                    )?>
                                                </span>
                                                <span class="grey-text darken-1"><i class="tiny material-icons">date_range</i>
                                                <?= h($comment->created->format('Y/m/d')) ?></span>
                                                <?php if (isset($login_user)): ?>
                                                    <?php if ($login_user->id === $comment->user_id) :?>
                                                        <?= $this->Form->postLink('削除<i class="material-icons left">delete</i>', 
                                                            ['controller' => 'Comments', 'action' => 'delete', $comment->id],
                                                            ['escape' => false, 'class' => 'btn-flat white red-text btn-small right hide-on-small-only light-blue lighten-5',
                                                            'confirm' => 'このコメントを削除します。本当によろしいですか？']
                                                        )?>
                                                        <?= $this->Html->link('編集する<i class="material-icons left">create</i>', 
                                                            ['controller' => 'Comments', 'action' => 'edit', $comment->id],
                                                            ['escape' => false, 'class' => 'btn red accent-2 btn-small right hide-on-small-only']
                                                        )?>
                                                        <i class='dropdown-trigger-comment material-icons right hide-on-med-and-up' data-target='dropdown-comment'>dehaze</i>

                                                        <ul id='dropdown-comment' class='dropdown-content'>
                                                            <li>
                                                                <?= $this->Html->link('<i class="material-icons tiny">create</i>編集', 
                                                                    ['controller' => 'Comments', 'action' => 'edit', $comment->id],
                                                                    ['escape' => false, 'class' => 'black-text']
                                                                )?>
                                                            </li>
                                                            <li>
                                                                <?= $this->Form->postLink('<i class="material-icons tiny">delete</i>削除', 
                                                                    ['controller' => 'Comments', 'action' => 'delete', $comment->id],
                                                                    ['escape' => false, 'class' => 'white red-text',
                                                                    'confirm' => 'このコメントを削除します。本当によろしいですか？'],
                                                                )?>
                                                            </li>
                                                        </ul>
                                                    <?php endif ?>
                                                <?php endif ?>
                                            </div>
                                            <div class="card-action">
                                                <?= h($comment->body) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        この記事にはまだコメントがありません。
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card">
                <?php if (isset($login_user)) :?>
                    <div class="card-content">
                        <span class="card-title"><h5><i class="material-icons">near_me</i>コメントを投稿する</h5></span>
                        <?= $this->Form->create($new_comment, ['url' => ['controller' => 'comments', 'action' => 'add']]) ?>
                        <?= $this->Form->hidden('article_id', ['value' => $article->id]) ?>
                        <?= $this->Form->control('body', ['label' => 'コメント', 'class' => 'materialize-textarea']) ?>
                    </div>
                    <div class="card-action">
                        <?= $this->Form->button('投稿', ['class' => 'btn blue btn-large']) ?> 
                        <?= $this->Form->end() ?>
                    </div>
                <?php else: ?>
                    <div class="card-content">
                        <span class="card-title"><h5><i class="material-icons">near_me</i>コメントを投稿する</h5></span>
                        <?= $this->Html->link('ログイン', ['controller' => 'Users', 'action' => 'login'])?>してコメントを投稿する
                        <?= $this->Form->control('body', ['label' => 'コメント', 'disabled' => 'disabled']) ?>
                    </div>
                    <div class="card-action">
                        <?= $this->Form->button('投稿', ['class' => 'btn btn-large disabled', ]) ?> 
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<script>
Vue.use(window['MavonEditor'])

const body = document.getElementById('body').value
new Vue({
   el: '#app',
   data() {
        return { 
            value: body,
            message: '',
            defaultOpen: 'preview',
            subfield: false,
            previewBackground: "#fff"
        }
    },
})

document.addEventListener('DOMContentLoaded', function() {
    const dropdownArticle = document.querySelector('.dropdown-trigger-article');
    M.Dropdown.init(dropdownArticle);
    const dropdownComment = document.querySelectorAll('.dropdown-trigger-comment');
    M.Dropdown.init(dropdownComment);
  });
</script>
<style>
    .btn-floating{
        margin: 5px 0;
    }
    .count {
        margin: 0;
    }
    .dropdown-content li>a>i {
        margin: 0!important;
    }
</style>