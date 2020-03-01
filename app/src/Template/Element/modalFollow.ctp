
<div id="follow-modal">
    <div id="follows" class="modal">
        <div class="modal-content">
            <h4>フォロー</h4>
            <?= $this->element('loader') ?>
            <div v-else>
                <ul class="collection">
                    <span v-for="follow in follows"
                    :key="follow.id"
                    :follow="follow"
                    >
                        <li class="collection-item avatar">
                            <img :src=`/img/${follow.follow_user.image}` :alt="follow.follow_user.username" class="circle">
                            <span class="title">{{ follow.follow_user.username }}</span>
                            <div>{{ follow.follow_user.description }}</div>
                        </li>   
                    </span>
                </ul>
            </div>
        </div>
    </div>
    <div id="followers" class="modal">
        <div class="modal-content">
            <h4>フォロワー</h4>
            <?= $this->element('loader') ?>
            <div v-else>
                <ul class="collection">
                    <span v-for="follower in followers"
                    :key="follower.id"
                    :follow="follower"
                    >
                        <li class="collection-item avatar">
                            <img :src=`/img/${follower.user.image}` :alt="follower.user.username" class="circle">
                            <span class="title">{{ follower.user.username }}</span>
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
                    console.log(data)
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