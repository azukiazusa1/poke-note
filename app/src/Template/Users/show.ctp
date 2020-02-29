<div class="container">
    <div class="row">
        <div class="col m4 s12">
            <div class="card">
                <div class="card-image">
                    <?= $this->Html->image($user->image, [
                        'alt' => 'Author',
                        'class' => 'materialboxed responsive-img circle mypage-img',
                    ])?>
                    <span class="card-title grey-text">@<?= h($user->username) ?></span>
                </div>
                <span class="card-title">
                    <?= h($user->nickname) ?>
                    <?php if (isset($login_user->id) && ($user->id === $login_user->id)): ?>
                        <p><?= $this->Html->link('プロフィールを編集', ['controller' => 'Users', 'action' => 'edit'], ['class' => 'btn'])?></p>
                    <?php else: ?>
                        <span class="" id="follow-btn">
                            <?php if ($isFollowed) : ?>
                                <a href="#" class="btn right rounded red accent-2 waves-effect waves-light">フォロー中</a>
                            <?php else: ?>
                                <a href="#" class="btn right rounded red-text accent-2-text white waves-effect waves-red">フォロー</a>
                            <?php endif ?>
                        </span>
                    <?php endif ?>
                </span>
                <div class="card-content">
                    <p><?= h($user->description) ?></p>
                </div>
                <div class="card-action">
                    <i class="tiny material-icons red-text text-accent-2">thumb_up</i><?= h($favorite_count) ?><br>
                    <span class="bold">フォロー</span><a class="modal-trigger" href="#modal-follow">100</a>
                    <div id="modal-follow" class="modal">
                        <div class="modal-content">
                            <h4>Modal Header</h4>
                            <p>A bunch of text</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
                        </div>
                    </div>
                    <span class="bold">フォロワー</span><a class="modal-trigger" href="#modal-follower">100</a>
                    <div id="modal-follower" class="modal">
                        <div class="modal-content">
                            <h4>Modal Header</h4>
                            <p>A bunch of text</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <ul class="collection with-header">
                        <li class="collection-header"><h4>人気の記事</h4></li>
                        <?php foreach ($popular_articles as $popular_article): ?>
                            <li class="collection-item">
                                <?= $this->Html->link(h($popular_article->title), ['controller' => 'articles', 'action' => 'show', h($popular_article->id)], ['class' => 'title']) ?>
                                <span class="right"><i class="tiny material-icons red-text text-accent-2">thumb_up</i>
                                <?= h($popular_article->favorite_count) ?></span>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col m8 s12">
            <div class="card" id="app">
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                        <li class="tab" @click="changeArticles"><a class="active" href="#articles">投稿した記事
                            <span class="btn-floating btn-small red accent-2"><?= h($user->article_count)?></span></a></li>
                        <li class="tab" @click="changeFavorites"><a href="#favorites">いいねした記事</a></li>
                        <li class="tab" @click="changeComments"><a href="#comments">コメント</a></li>
                    </ul>
                </div>
                <div class="card-content">
                    <div id="articles">
                        <?= $this->element('loader') ?>
                        <div v-else-if="isEmptyArticles">投稿した記事はありません。</div>
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
                    <div id="favorites">
                        <?= $this->element('loader') ?>
                        <div v-else-if="isEmptyFavorites">いいねした記事はありません。</div>
                        <div v-else>
                            <ul class="collection">
                                <articles
                                    v-for="article in favorites"
                                    :key="article.id"
                                    :article="article"
                                />
                            </ul>
                            <div class="center">
                                <pagination
                                    :paging="favoritesPaging"
                                    @paginate="favoritesPage = $event"
                                />
                            </div>
                        </div>
                    </div>
                    <div id="comments">
                        <?= $this->element('loader') ?>
                        <div v-else-if="isEmptyComments">コメントはありません。</div>
                        <div v-else>
                            <ul class="collection">
                                <comments
                                    v-for="comment in comments"
                                    :key="comment.id"
                                    :comment="comment"
                                />
                            </ul>
                            <div class="center">
                                <pagination
                                    :paging="commentsPaging"
                                    @paginate="commentsPage = $event"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.Materialbox.init(document.querySelector('.materialboxed'));
        M.Tabs.init(document.querySelectorAll('.tabs'));
        M.Modal.init(document.querySelectorAll('.modal'));
  });
</script>
<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<script type="text/x-template" id="article-template">
    <li class="collection-item avatar">
        <a :href="userUrl"><img class="circle responsive-img" :src="userImg" /></a>
        <a :href="articleUrl" class="title">{{ article.title }}</a><br>
        <div class="grey-text TagList">
            <i class="tiny material-icons grey-text">local_offer</i>
            <Tag-list v-for="tag in article.tags" :key="tag.id" :tag="tag" class="tag" />
        </div>
        <div>
            <span>
                <a :href="userUrl">@{{article.user.username}}</a>
            </span>
            <span class="grey-text">
                <i class="tiny material-icons red-text text-accent-2">thumb_up</i>
                {{ article.favorite_count }}
            </span>
            <span class="grey-text">
                <i class="tiny material-icons teal-text text-lighten-2">comment</i>
                {{ article.comment_count }}
            </span>
            <span class="grey-text darken-1 hide-on-small-only">
                <i class="tiny material-icons">date_range</i>
                {{ article.formated_created }}
            </span>
            <p class="grey-text darken-1 hide-on-med-and-up">
                <i class="tiny material-icons">date_range</i>
                {{ article.formated_created }}
            </p>
        </div>
    </li>
</script>
<script type="text/x-template" id="comment-template">
    <li class="collection-item avatar">
        <div>
            <a :href="userUrl"><img class="circle responsive-img" :src="userImg" /></a>
            <a :href="articleUrl" class="title">{{ comment.article.title }}</a>にコメントしました。
            <span class="grey-text darken-1 right">
                <i class="tiny material-icons">date_range</i>
                {{ comment.formated_created }}
            </span>
        </div>
        <div class="truncate">
            {{ comment.body }}
        </div>
    </li>
</script>
<script type="text/x-template" id="pagination-template">
    <ul class="pagination">
        <li class="waves-effect" v-if="paging.prevPage" @click="$emit('paginate', paging.page - 1)">
            <a href="#!"><i class="material-icons">chevron_left</i></a>
        </li>
        <span v-for="n in paging.pageCount" :n="n">
            <li :class="{ 'active': (paging.page === n), 'waves-effect': (paging.page !== n) }">
                <a href="#!" @click="$emit('paginate', n)">{{ n }}</a>
            </li>
        </span>
        <li class="waves-effect" v-if="paging.nextPage" @click="$emit('paginate', paging.page + 1)">
            <a href="#!"><i class="material-icons">chevron_right</i></a>
        </li>
    </ul>
</script>
<script>
    new Vue({
        el: '#app',
        data() {
            return { 
                articles: '',
                articlesPage: 1,
                articlesPaging: '',
                favorites: '',
                favoritesPage: 1,
                favoritesPaging: '',
                comments: '',
                commentsPage: 1,
                commentsPaging: '',
                loading: true,
                userId: '<?= $user->id ?>'
            }
        },
        created() { this.fetchArticles() },
        methods: {
            fetchArticles: async function() {
                try {
                    this.loading = true
                    const {data} = await axios.get(`/api/users/${this.userId}/articles.json?page=${this.articlesPage}`)
                    this.articles = data.articles
                    this.articlesPaging = data.paging
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
            fetchFavorites: async function() {
                try {
                    this.loading = true
                    const {data} = await axios.get(`/api/users/${this.userId}/favorites.json?page=${this.favoritesPage}`)
                    this.favorites = data.favorites
                    this.favoritesPaging = data.paging
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
            fetchComments: async function() {
                try {
                    this.loading = true
                    const {data} = await axios.get(`/api/users/${this.userId}/comments.json?page=${this.commensPage}`)
                    this.comments = data.comments
                    this.commentsPaging = data.paging
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
            changeArticles: function() {
                if (this.articles) {
                    this.loading = false
                    return
                }
                this.fetchArticles()
            },
            changeFavorites: function() {
                if (this.favorites) {
                    this.loading = false
                    return
                }
                this.fetchFavorites()
            },
            changeComments: function() {
                if (this.comments) {
                    this.loading = false
                    return
                }
                this.fetchComments()
            },
        },
        watch: {
            articlesPage: function() {
                this.fetchArticles()
            },
            favoritesPage: function()  {
                this.fetchFavorites()
            },
            commentsPage: function() {
                this.fetchComments()
            }
        },
        computed: {
            isEmptyArticles: function() {
                return this.articles.length < 1
            },
            isEmptyFavorites: function() {
                return this.favorites.length < 1
            },
            isEmptyComments: function() {
                return this.comments.length < 1
            }
        }
    })
    
    Vue.component('articles', {
        props: ['article'],
        data() {
            return {
                userImg: `/img/${this.article.user.image}`,
                userUrl: `/users/${this.article.user.username}`,
                articleUrl: `/articles/show/${this.article.id}`
            }
        },
        template: '#article-template'
    })

    Vue.component('Tag-list', {
        props: ['tag'],
        data() {
            return {
                tagUrl: `/tags/${this.tag.title}`
            }
        },
        template: `
            <span>
                <a :href="tagUrl" class="tag-color">{{ tag.title }}</a>
            </span>
        `
    })

    Vue.component('comments', {
        props: ['comment'],
        data() {
            return {
                userUrl: `/users/${this.comment.user.username}`,
                userImg: `/img/${this.comment.user.image}`,
                articleUrl: `/articles/show/${this.comment.article.id}`
            }
        },
        template: '#comment-template'
    })

    Vue.component('pagination', {
        props: ['paging'],
        template: '#pagination-template'
    })
</script>