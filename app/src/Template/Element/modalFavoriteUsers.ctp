
<div id="favorite-users-modal">
    <div id="favorites" class="modal">
        <div class="modal-content">
            <h1>いいねしたユーザー</h1>
            <?= $this->element('loader') ?>
            <div v-else>
                <ul class="collection">
                    <user-list v-for="user in favorites"
                    :key="user.id"
                    :user="user"
                    />
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->element('User-list') ?>

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