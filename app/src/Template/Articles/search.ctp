<?php $this->assign('title', '記事一覧 | PNote!') ?>
<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<div class="container" id="app">
    <h1>記事一覧</h1>
    <div class="row hide-on-med-and-up">
        {{ paging.count }}記事
    </div>
    <div class="row">
        <div class="col m10 s12">
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
            <div id="scroll-trigger" ref="infinitescrolltrigger">
                <div class="center" v-if="paging.nextPage">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        <form @submit.prevent="onSubmit" action="#">
            <div class="col m2 hide-on-small-only">
                <div class="pinned">
                    <div class="row">
                        {{ paging.count }}記事
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="search" class="validate" v-model="q">
                            <button class="btn waves-effect waves-light grey lighten-2" type="submit">
                                <i class="material-icons black-text">search</i>
                            </button>
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
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" class="validate" v-model="params.start_date">
                            <label>投稿日(以降)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" class="validate" v-model="params.end_date">
                            <label>投稿日(以前)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row hide-on-med-and-up pinned white z-depth-5 search-box">
            <div class="input-field col s10">
                <i class="material-icons prefix">search</i>
                <input type="search" class="validate" v-model="q">
            </div>
            <div class="col s2">
                <a href="#" data-target="modal-search" class="modal-trigger">
                    <i class="material-icons medium black-text">more_vert</i>
                </a>
            </div>
            <div id="modal-search" class="modal">
                <div class="modal-content">
                    <h4>詳細検索</h4>
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
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" class="validate" v-model="params.start_date">
                            <label>投稿日(以降)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" class="validate" v-model="params.end_date">
                            <label>投稿日(以前)</label>
                        </div>
                    </div>
                </div>
                    <div class="modal-footer">
                        <a href="#!" class="modal-close"><i class="material-icons black-text">close</i></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->element('Article-list') ?>
<?= $this->element('Tag-list') ?>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                articles: [],
                paging: {
                    nextPage: null,
                    count: 0,
                },
                q: '<?= $q ?>',
                params: {
                    start_date: '',
                    end_date: '',
                    sort: 'created',
                    direction: 'desc',
                },
                page: 1,
                loading: true,
            }
        },
        created() { 
            this.fetchArticles()
        },
        mounted() {
            this.scrollTrigger()
        },
        methods: {
            fetchArticles: async function() {
                try {
                    const {data} = await axios.get('/api/articles.json', {
                        params: {
                            q: this.q,
                            page: this.page,
                            ...this.params,
                        }
                    })
                    this.articles.push(...data.articles)
                    this.paging = data.paging
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
            scrollTrigger() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if(entry.intersectionRatio > 0 && this.paging.nextPage) {
                            this.page++
                            this.fetchArticles()
                        }
                    });
                });
            observer.observe(this.$refs.infinitescrolltrigger);
            },
            onSubmit() {
				if (this.loading) return 
				this.clearSearch()
				this.fetchArticles()
			},
			clearSearch() {
				this.page = 1
				this.paging = null
				this.paging =  {
					nextPage: null,
					count: 0,
				}
				this.articles.splice(0)
				this.loading = true
			}
        },
        watch: {
            params: {
                handler: function () {
                    if (this.loading) return 
				    this.clearSearch()
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.Modal.init(document.querySelectorAll('.modal'));
    });
</script>