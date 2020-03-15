<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<div class="container" id="app">
    <h1>タグ一覧</h1>
    <div class="row">
        <p>{{ paging.count }}個のタグが見つかりました。</p>
        <p>気になるタグをフォローしましょう。</p>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix">search</i>
            <input type="text" class="validate" v-model="params.q">
        </div>
    </div>
    <?= $this->element('loader') ?>
    <div v-else-if="isEmptyTags">タグが見つかりませんでした。</div>
    <div class="row" v-else>
        <span 
            class="col m3 s6"
            v-for="tag in tags"
            :key="tag.id"
            :tag="tag"
        >
            <a :href=`/tags/${tag.title}`>
                <span class="chip">
                    {{ tag.title }}
                    <span class="fa-stack fa-lg" style="font-size: 1em;">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-inverse fa-stack-1x">{{ tag.article_count }}</i>
                    </span>
                </span>
            </a>
        </span>
    </div>
    <div class="row">
        <div id="scroll-trigger" ref="infinitescrolltrigger">
            <div class="center" v-if="paging.nextPage">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                tags: [],
                paging: {
                    nextPage: null,
                    count: 0,
                },
                params: {
                    q: '',
                },
                page: 1,
                loading: true,
            }
        },
        created() { 
            this.fetchTags()
        },
        mounted() {
            this.scrollTrigger()
        },
        methods: {
            fetchTags: async function() {
                try {
                    const {data} = await axios.get('/api/tags.json', {
                        params: {
                            page: this.page,
                            ...this.params,
                        }
                    })
                    this.tags.push(...data.tags)
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
                            this.fetchTags()
                        }
                    });
                });
            observer.observe(this.$refs.infinitescrolltrigger);
            },
        },
        watch: {
            params: {
                handler: function () {
                    this.page = 1
                    this.tags.splice(0)
                    this.loading = true
                    this.fetchTags()
                },
                deep: true
            },
        },
        computed: {
            isEmptyTags: function() {
                return this.tags.length < 1
            },
        }
    })
</script>