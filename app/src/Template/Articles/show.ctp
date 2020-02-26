<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<div class="container">
    <div class="row">
        <div class="col m1 hide-on-small-only">
            <ul>
                <li><a class="btn-floating btn-large red accent-2 z-depth-3"><i class="material-icons">thumb_up</i></a></li>
                <li><a class="btn-floating btn-large z-depth-3"><i class="fab fa-twitter twitter"></i></a></li>
                <li><a class="btn-floating btn-large z-depth-3"><i class="fab fa-facebook-f facebook"></i></a></li>
                <li><a class="btn-floating btn-large z-depth-3"><i class="material-icons">comment</i></a></li>
        </div>
        <div class="col s12 m11">
            <div class="card">
                <div class="card-content">
                    <span><?= $this->Html->image(h($article->user->image), [
                        'alt' => 'user',
                        'class' => 'responsive-img circle icon-image',
                    ])?></span>
                    <span>　@<?= h($article->user->username) ?></span>
                    <span class="grey-text darken-1 hide-on-small-only">　<i class="tiny material-icons">date_range</i>
                    <?= h($article->created->format('Y/m/d')) ?></span>
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
                                    'controller' => 'tags', 'action' => 'search', h($tag->title)
                                ])?>
                            </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <span class="small-text">この記事にタグはありません。</span>
                    <?php endif ?>
                    <br>
                    <a class="btn-floating red accent-2 hide-on-med-and-up"><i class="material-icons">thumb_up</i></a>
                    <a class="btn-floating hide-on-med-and-up"><i class="fab fa-twitter twitter"></i></a>
                    <a class="btn-floating hide-on-med-and-up"><i class="fab fa-facebook-f facebook"></i></a>
                    <a class="btn-floating hide-on-med-and-up"><i class="material-icons">comment</i></a>
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
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
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
</script>
<style>
    .btn-floating{
        margin: 5px;
    }
    .dropdown-content li>a>i {
        margin: 0!important;
    }
</style>