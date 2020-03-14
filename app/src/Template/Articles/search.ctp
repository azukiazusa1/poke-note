<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<div class="container" id="app">
    <div class="row">
        <div class="col m10">
            <?= $this->element('loader') ?>
            <div v-else-if="isEmptyArticles">記事が見つかりませんでした。</div>
            <div v-else>
                <ul class="collection">
                    <articles
                        v-for="article in articles"
                        :key="article.id"
                        :article="article"
                    />
                </ul>
            </div>
        </div>
        <div class="col m2">
            <div class="pinned">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="validate" v-model="params.q">
                    </div>
                </div>
                <div class="row">
                <div class="input-field col s12">
                    <select v-model="params.sort">
                        <option value="created">新着順</option>
                        <option value="favorite_count">いいね数順</option>
                        <option value="comment_count">コメント数順</option>
                    </select>
                    <label>並び順</label>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->element('Article-list') ?>
<?= $this->element('Tag-list') ?>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                articles: '',
                params: {
                    q: '<?= $q ?>',
                    sort: 'created',
                    direction: 'desc'
                },
                loading: true,
            }
        },
        created() { this.fetchArticles() },
        methods: {
            fetchArticles: async function() {
                try {
                    this.loading = true
                    const {data} = await axios.get('/api/articles.json', {
                        params: this.params
                    })
                    this.articles = data.articles
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
        },
        watch: {
            params: {
                handler: function () {
                    this.fetchArticles()
            },
            deep: true
        },
        },
        computed: {
            isEmptyArticles: function() {
                return this.articles.length < 1
            },
        }
    })
</script>