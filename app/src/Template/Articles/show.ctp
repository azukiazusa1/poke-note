<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<div class="container">
    <div class="hide-on-med-and-down fixed-btn">
            <p>
                <a class="btn-floating btn-large waves-accent-2 z-depth-3 like-btn waves-effect
                 <?= $isFavorite ? 'red' : 'grey' ?>"><i class="material-icons">thumb_up</i></a>
                <span class="bold"><?= h($article->favorite_count) ?></span>
            </p>
            <p>
                <a class="btn-floating btn-large z-depth-3" href="#comment"><i class="material-icons">comment</i></a>
                <span class="bold"><?= h($article->comment_count) ?></span>
            </p>
            <p>
                <a class="btn-floating btn-large z-depth-3" 
                href="http://twitter.com/share?url=<?= $this->Url->build(['controller' => 'Articles', 'action' => 'show', $article->id], true) ?>&text=<?= $article->title ?>"
                ><i class="fab fa-twitter twitter"></i>
                </a>
            </p>
            <p><a class="btn-floating btn-large z-depth-3"><i class="fab fa-facebook-f facebook"></i></a></p>
    </div>
    <div class="row">
        <div class="col s12 m11">
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
                        <?= $this->element('edit-delete-btn', ['id' => $article->id]) ?>
                    <?php endif ?>
                    <p class="grey-text darken-1 hide-on-med-and-up">　
                            <i class="tiny material-icons">date_range</i>
                            <?= h($article->created->format('Y/m/d')) ?>
                    </p>
                    <hr class="list-divider">
                    <span class="card-title">
                        <?php if ($article->isDraft()) :?>
                            <h4 class="text-red">この記事はまだ公開されていません。</h4>
                        <?php endif ?>
                        <h1><?= h($article->title) ?></h1>
                    </span>
                    <?= $this->element('tags', ['article' => $article]) ?>
                    <br>
                    <div class="hide-on-large-only">
                        <a class="btn-floating like-btn waves-effect
                        <?= $isFavorite ? 'red' : 'grey' ?>"><i class="material-icons">thumb_up</i></a>
                        <span class="bold"><?= h($article->favorite_count) ?></span>
                        <a class="btn-floating" href="#comment"><i class="material-icons">comment</i></a>
                        <span class="bold"><?= h($article->comment_count) ?></span>
                        <a class="btn-floating"
                        href="http://twitter.com/share?url=<?= $this->Url->build(['controller' => 'Articles', 'action' => 'show', $article->id], true) ?>&text=<?= $article->title ?>"
                        ><i class="fab fa-twitter twitter"></i></a>
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
                            :navigation=true
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m11">
            <div class="card">
                <div class="card-content">
                    <h5 id="comment"><i class="material-icons Medium">comment</i>コメント一覧</h5>
                    <?php if (count($article->comments) > 0) : ?>
                        <?php foreach ($article->comments as $comment) :?>
                            <div class="row" id="<?= $comment->id ?>">
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
                                                        <a class="btn red accent-2 btn-small right hide-on-small-only edit-comment" data-target="<?= $comment->id ?>">編集する<i class="material-icons left">create</i></a> 
                                                        <i class='dropdown-trigger-comment material-icons right hide-on-med-and-up' data-target='dropdown-comment'>dehaze</i>

                                                        <ul id='dropdown-comment' class='dropdown-content'>
                                                            <li>
                                                                <a class="edit-comment" data-target="<?= $comment->id ?>"><i class="material-icons tiny">create</i>編集</a>
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
                                        <?php if (isset($login_user)): ?>
                                            <?php if ($login_user->id === $comment->user_id) :?>
                                                <div class="card-content hide">
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
                                                    <span class="bold right">コメントを編集<span>
                                                </div>
                                                <div class="card-action hide">
                                                    <?= $this->Form->create($comment, ['url' => ['controller' => 'comments', 'action' => 'edit']]) ?>
                                                    <?= $this->Form->control('body', ['label' => 'コメント', 'class' => 'white materialize-textarea', 'id' => "comment{$comment->id}"]) ?>
                                                    <?= $this->Form->button('編集', ['class' => 'btn blue right']) ?>
                                                    <a class="btn white black-text right cancel-btn" data-target="<?= $comment->id ?>">キャンセル</a> 
                                                    <?= $this->Form->end() ?>    
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                <?php endif ?>
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
        <div class="col s12 m11">
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
<?= $this->element('modalUnlogin', ['do' => 'いいね']) ?>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
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

    const articleId = '<?= $article->id ?>'
    const likeBtns = document.querySelectorAll('.like-btn')
    likeBtns.forEach(likeBtn => {
        likeBtn.addEventListener('click', async function() {
            try {
                const {data} = await axios.post(`/api/articles/${articleId}/favorites.json`)
                    if (data.message === 'Saved') {
                        this.nextElementSibling.innerText++
                        this.classList.remove('grey')
                        this.classList.add('red')
                    } else if (data.message === 'Deleted'){
                        this.nextElementSibling.innerText--
                        this.classList.add('grey')
                        this.classList.remove('red')
                    }
            } catch ({response}) {
                const modal = document.getElementById('modal-unlogin')
                M.Modal.init(modal);
                const instance = M.Modal.getInstance(modal);
                if (response.status === 401) {
                    instance.open()
                } else {
                    M.toast({html: '予期せぬエラーが発生しました。', classes: 'rounded red lighten-4 red-text darken-2-text'})
                }
            }
        })
    })

    function toggleHide() {
        const targetId = this.dataset.target
        const comment = document.getElementById(targetId)
        const contents = [...comment.querySelectorAll('.card-content')];
        const actions = [...comment.querySelectorAll('.card-action')];
        contents.map(content => content.classList.toggle('hide'))
        actions.map(action => action.classList.toggle('hide'))
    }

    const editBtns = document.querySelectorAll('.edit-comment')
    editBtns.forEach(editBtn => {
        editBtn.addEventListener('click', toggleHide)
    })

    const cancelBtns = document.querySelectorAll('.cancel-btn')
    cancelBtns.forEach(cancelBtn => {
        cancelBtn.addEventListener('click', toggleHide)
    })
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

    .v-note-wrapper {
        z-index: 100!important;
    }
</style>