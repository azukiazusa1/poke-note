<div class="container">
    <div class="row">
        <div class="col s12" id="app">
            <h4>下書き一覧</h4>
            <ul class="collapsible">
                <?php foreach ($articles as $article): ?>
                    <li>
                        <div class="collapsible-header">
                            <?php if ($article->title): ?>
                                <span class="left"><?= h($article->title) ?></span>
                            <?php else: ?>
                                <span class="grey-text lighten-1-text">タイトル未設定</span>
                            <?php endif ?>
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