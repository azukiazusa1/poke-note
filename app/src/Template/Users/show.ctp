<?php $this->assign('title', h($user->username) . ' | PNote!') ?>
<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
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
                                <a href="#" class="btn right rounded red-text accent-2-text white waves-effect waves-red hide">フォロー</a>
                            <?php else: ?>
                                <a href="#" class="btn right rounded red accent-2 waves-effect waves-light hide">フォロー中</a>
                                <a href="#" class="btn right rounded red-text accent-2-text white waves-effect waves-red">フォロー</a>
                            <?php endif ?>
                        </span>
                    <?php endif ?>
                </span>
                <div class="card-content">
                    <?php if ($user->link): ?>
                        <div><i class="fas fa-link"></i><?= $this->Html->link(h($user->link), $user->link) ?></div>
                    <?php endif ?>
                    <div><?= h($user->description) ?></div>
                </div>
                <div class="card-action">
                    <i class="tiny material-icons red-text text-accent-2">thumb_up</i><?= h($user->total_favorite) ?><br>
                    <a class="modal-trigger" href="#follows">
                        <span class="bold black-text">フォロー</span><?= h($user->follow_count) ?></a>
                    <a class="modal-trigger" href="#followers">
                        <span class="bold black-text">フォロワー</span><?= h($user->follower_count) ?></a>
                    <?= $this->element('modalFollow', ['type' => 'followers', 'title' => 'フォロワー']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <ul class="collection with-header">
                        <li class="collection-header"><h1>人気の記事</h1></li>
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
                        <li class="tab" @click="change('articles')"><a class="active" href="#articles">投稿した記事
                            <?= $this->element('number_circle', ['n' => $user->article_count]) ?>
                        </a></li>
                        <li class="tab" @click="change('favorites')"><a href="#favorites">いいねした記事
                            <?= $this->element('number_circle', ['n' => $user->favorite_count]) ?>
                        </a></li>
                        <li class="tab" @click="change('comments')"><a href="#comments">コメント
                            <?= $this->element('number_circle', ['n' => $user->comment_count]) ?>
                        </a></li>
                        <li class="tab" @click="change('tags')"><a href="#tags">フォロータグ
                            <?= $this->element('number_circle', ['n' => $user->follow_tag_count]) ?>
                        </a></li>
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
                                <comments-list
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
                    <div id="tags">
                        <?= $this->element('loader') ?>
                        <div v-else-if="isEmptyTags">フォローしているタグはありません。</div>
                        <div v-else>
                            <div class="grey-text TagList">
                                <i class="tiny material-icons grey-text">local_offer</i>
                                <Tag-list v-for="tag in tags" :key="tag.id" :tag="tag" class="tag" />
                            </div>
                            <div class="center">
                                <pagination
                                    :paging="tagsPaging"
                                    @paginate="tagsPage = $event"
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
<?= $this->element('Comment-list') ?>
<?= $this->element('pagination') ?>
<script>
    const userId = '<?= $user->id ?>'
    document.addEventListener('DOMContentLoaded', function() {
        M.Materialbox.init(document.querySelector('.materialboxed'));
        M.Tabs.init(document.querySelectorAll('.tabs'));
        M.Modal.init(document.querySelectorAll('.modal'));

        const followBtn =  document.getElementById('follow-btn')
        followBtn.addEventListener('click', async function() {
            try {
                const {data} = await axios.post(`/api/users/${userId}/follows.json`)
                const children = [...this.children];
                children.map(child => child.classList.toggle('hide'))
            } catch ({response}) {
                const modal = document.getElementById('modal-unlogin')
                M.Modal.init(modal);
                const instance = M.Modal.getInstance(modal);
                if (response.status === 401) {
                    instance.open()
                    return 
                } else if (response.status === 400) {
                    const err = '自分自身をフォローすることはできません。'
                } else {
                    const err = '予期せぬエラーが発生しました。'
                }

                M.toast({html: err, classes: 'rounded red lighten-4 red-text darken-2-text'})
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
                articlesPage: 1,
                articlesPaging: '',
                favorites: '',
                favoritesPage: 1,
                favoritesPaging: '',
                comments: '',
                commentsPage: 1,
                commentsPaging: '',
                tags: '',
                tagsPage: 1,
                tagsPaging: '',
                loading: true,
                userId: userId
            }
        },
        created() { this.fetch('articles') },
        methods: {
            fetch: async function(name) {
                try {
                    this.loading = true
                    const {data} = await axios.get(`/api/users/${this.userId}/${name}.json?page=${this[`${name}Page`]}`)
                    this[name] = data[name]
                    this[`${name}Paging`] = data.paging
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            },
            change: function(name) {
                if (this[name]) {
                    this.loading = false
                    return
                }
                this.fetch(name)
            },
        },
        watch: {
            articlesPage: function() {
                this.fetch('articles')
            },
            favoritesPage: function()  {
                this.fetch('favorites')
            },
            commentsPage: function() {
                this.fetch('comments')
            },
            tagsPage: function() {
                this.fetch('tags')
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
            },
            isEmptyTags: function() {
                return this.tags.length < 1
            }
        }
    })
</script>