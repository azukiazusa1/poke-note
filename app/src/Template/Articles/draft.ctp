<?php $this->assign('title', '下書き一覧 | PNote!') ?>
<div class="container">
    <div class="row">
        <div class="col s12" id="app">
            <h1>下書き一覧</h1>
            <?php if ($articles->count() < 1): ?>
                <div>下書きはありません。</div>
            <?php endif ?>
            <ul class="collapsible">
                <?php foreach ($articles as $article): ?>
                    <li>
                        <div class="collapsible-header">
                            <?php if ($article->title): ?>
                                <span class="left"><?= h($article->title) ?></span>
                            <?php else: ?>
                                <span class="grey-text lighten-1-text">タイトル未設定</span>
                            <?php endif ?>
                            <span class="grey-text lighten-1-text">
                                <i class="tiny material-icons">date_range</i>
                                <?= h($article->created->format('Y/m/d H:i:s')) ?>
                            </span>
                        </div>
                        <div class="collapsible-body">
                            <div>
                                <?= $this->element('tags', ['article' => $article]) ?>
                                <?= $this->element('edit-delete-btn', ['id' => $article->id]) ?>
                            </div>
                            <div style="clear: both">
                                <mavon-editor 
                                    language="ja" 
                                    value="<?= $article->body ?>"
                                    :default-open="defaultOpen"
                                    :toolbars-flag=false
                                    :subfield="subfield"
                                    :box-shadow=false
                                    :preview-background="previewBackground"
                                />
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.collapsible');
        var options = [];
        var instances = M.Collapsible.init(elems, options);
        const dropdownArticle = document.querySelectorAll('.dropdown-trigger-article');
        M.Dropdown.init(dropdownArticle);
  });
    Vue.use(window['MavonEditor'])

    new Vue({
    el: '#app',
    data() {
            return {
                message: '',
                defaultOpen: 'preview',
                subfield: false,
                previewBackground: "#fff"
            }
        },
    })
</script>