
<div id="favorite-users-modal">
    <div id="favorites" class="modal">
        <div class="modal-content">
            <h4>いいねしたユーザー</h4>
            <?= $this->element('loader') ?>
            <div v-else>
                <ul class="collection">
                    <span v-for="favorite in favorites"
                    :key="favorite.id"
                    :favorite="favorite"
                    >
                        <li class="collection-item avatar">
                            <a :href=`/users/${favorite.user.username}`><img :src=`/img/${favorite.user.image}` :alt="favorite.user.username" class="circle"></a>
                            <a class="title" :href=`/users/${favorite.user.username}`>{{ favorite.user.username }}</a>
                            <div>{{ favorite.user.description }}</div>
                        </li>   
                    </span>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#favorite-users-modal',
        data() {
            return {
                favorites: '',
                followers: '',
                articleId: '<?= $article->id ?>',
                loading: true
            }
        },
        created() { this.fetchFavorites() },
        methods: {
            fetchFavorites: async function() {
                try {
                    const {data} = await axios.get(`/api/articles/${this.articleId}/favorites.json`)
                    this.favorites = data.favorites
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            }
        }

    })
</script>