<?php $this->assign('title', h($tag->title) . ' | PNote!') ?>
<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<div class="container">
    <div class="row">
        <div class="col m4 s12">
            <div class="card">
                <div class="card-content">
                    <h1 class="card-title" style="margin: 0;"><?= h($tag->title) ?></h1>
                    <div>
                        <?= h($tag->article_count) ?>記事
                        <span class="" id="follow-btn">
                            <?php if ($isFollowed) : ?>
                                <a href="#" class="btn right rounded red accent-2 waves-effect waves-light">フォロー中</a>
                                <a href="#" class="btn right rounded red-text accent-2-text white waves-effect waves-red hide">フォロー</a>
                            <?php else: ?>
                                <a href="#" class="btn right rounded red accent-2 waves-effect waves-light hide">フォロー中</a>
                                <a href="#" class="btn right rounded red-text accent-2-text white waves-effect waves-red">フォロー</a>
                            <?php endif ?>
                        </span>
                    </div>
                </div>
                <div class="card-action">
                    <h2>about</h2>
                    <i class="fa fa-question-circle tooltipped edit-list" aria-hidden="true" data-position="bottom" 
                        data-tooltip="このタグを使用したことがある場合、タグの説明を編集することができます。"></i>
                    <?php if($isUsedTag): ?>
                        <a class="btn red accent-2 btn-small edit-tag edit-list">編集する<i class="material-icons left">create</i></a> 
                    <?php endif ?>
                    <hr>
                    <div class="edit-list" style="word-wrap: break-word;"><?= nl2br(h($tag->description)) ?></div>
                    <div class="hide edit-list">
                        <?= $this->Form->create($tag, ['url' => ['controller' => 'Tags', 'action' => 'edit', $tag->id]]) ?>
                        <?= $this->Form->control('description', ['label' => 'タグの説明', 'type' => 'textarea', 
                        'class' => 'white materialize-textarea text', 'data-length' => "255", 'maxLength' => '255']) ?>
                        <a class="btn white black-text cancel-btn">キャンセル</a> 
                        <?= $this->Form->button('編集', ['class' => 'btn blue']) ?>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col m8 s12">
            <div class="card" id="app">
                <div class="card-content">
                    <span class="card-title">
                        記事一覧
                    </span>
                    <div id="articles">
                        <?= $this->element('loader') ?>
                        <div v-else-if="isEmptyArticles">投稿された記事はありません。</div>
                        <div v-else>
                            <ul class="collection">
                                <articles
                                    v-for="article in articles"
                                    :key="article.id"
                                    :article="article"
                                />
                            </ul>
                            <div class="center">
                                <pagination
                                    :paging="articlesPaging"
                                    @paginate="articlesPage = $event"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->element('modalUnlogin', ['do' => 'フォロー']) ?>
<?= $this->element('Article-list') ?>
<?= $this->element('Tag-list') ?>
<?= $this->element('pagination') ?>
<script>
    const tagId = '<?= $tag->id ?>'
    document.addEventListener('DOMContentLoaded', function() {
        M.Modal.init(document.querySelectorAll('.modal'));
        M.Tooltip.init(document.querySelectorAll('.tooltipped'));
        M.CharacterCounter.init(document.querySelectorAll('.text'))

        const followBtn =  document.getElementById('follow-btn')
        followBtn.addEventListener('click', async function() {
            try {
                const {data} = await axios.post(`/api/tags/${tagId}/users.json`)
                const children = [...this.children];
                children.map(child => child.classList.toggle('hide'))
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
       
       function toggleHide() {
            const ediList = document.querySelectorAll('.edit-list');
            ediList.forEach(e => e.classList.toggle('hide'))
        }

        const editBtn = document.querySelector('.edit-tag')
        editBtn.addEventListener('click', toggleHide)

        const cancelBtn = document.querySelector('.cancel-btn')
        cancelBtn.addEventListener('click', toggleHide)
  });
</script>
<script>
    new Vue({
        el: '#app',
        data() {
            return { 
                articles: '',
                page: 1,
                paging: '',
                loading: true,
                tagId: tagId
            }
        },
        created() { this.fetchArticles() },
        methods: {
            fetchArticles: async function() {
                try {
                    this.loading = true
                    const {data} = await axios.get(`/api/tags/${this.tagId}/articles.json?page=${this.articlesPage}`)
                    this.articles = data.articles
                    this.articlesPaging = data.paging
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
        },
        watch: {
            page: function() {
                this.fetchArticles()
            },
        },
        computed: {
            isEmptyArticles: function() {
                return this.articles.length < 1
            }
        }
    })
</script>