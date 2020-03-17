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
                <hr>
                <div style="word-wrap: break-word;"><?= h($tag->description) ?></div>
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

        const followBtn =  document.getElementById('follow-btn')
        followBtn.addEventListener('click', async function() {
            try {
                const {data} = await axios.post(`/api/tags/${tagId}/follows.json`)
                const children = [...this.children];
                children.map(child => child.classList.toggle('hide'))
            } catch ({response}) {
                const modal = document.getElementById('modal-unlogin')
                M.Modal.init(modal);
                const instance = M.Modal.getInstance(modal);
                if (response.status === 401) {
                    instance.open()
                } else {
                    M.toast({html: response.message, classes: 'rounded red lighten-4 red-text darken-2-text'})
                }
            }
       })
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