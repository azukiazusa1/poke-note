
<div id="follow-modal">
    <div id="follows" class="modal">
        <div class="modal-content">
            <h1>フォロー</h1>
            <?= $this->element('loader') ?>
            <div v-else>
                <ul class="collection">
                    <span v-for="follow in follows"
                    :key="follow.id"
                    :follow="follow"
                    >
                        <li class="collection-item avatar">
                            <a :href=`/users/${follow.follow_user.username}`><img :src=`/img/${follow.follow_user.image}` :alt="follow.follow_user.username" class="circle"></a>
                            <a class="title" :href=`/users/${follow.follow_user.username}`>{{ follow.follow_user.username }}</a>
                            <div>{{ follow.follow_user.description }}</div>
                        </li>   
                    </span>
                </ul>
            </div>
        </div>
    </div>
    <div id="followers" class="modal">
        <div class="modal-content">
            <h1>フォロワー</h1>
            <?= $this->element('loader') ?>
            <div v-else>
                <ul class="collection">
                    <span v-for="follower in followers"
                    :key="follower.id"
                    :follow="follower"
                    >
                        <li class="collection-item avatar">
                            <a :href=`/users/${follower.user.username}`><img :src=`/img/${follower.user.image}` :alt="follower.user.username" class="circle"></a>
                            <a class="title" :href=`/users/${follower.user.username}`>{{ follower.user.username }}</a>
                            <div>{{ follower.user.description }}</div>
                        </li>
                    </span>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#follow-modal',
        data() {
            return {
                follows: '',
                followers: '',
                userId: '<?= $user->id ?>',
                loading: true
            }
        },
        created() { this.fetchFollows() },
        methods: {
            fetchFollows: async function() {
                try {
                    const {data} = await axios.get(`/api/users/${this.userId}/follows.json`)
                    this.follows = data.follows
                    this.followers = data.followers
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            }
        }

    })
</script>