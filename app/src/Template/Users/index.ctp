<?php $this->assign('title', 'ユーザー一覧 | PNote!') ?>
<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<div class="container" id="app">
    <h1>ユーザー一覧</h1>
    <div class="row hide-on-med-and-up">
        {{ paging.count }}ユーザー
    </div>
    <div class="row">
        <div class="col m10 s12">
            <?= $this->element('loader') ?>
            <div v-else-if="isEmptyUsers">ユーザーが見つかりませんでした。</div>
            <div v-else>
				<ul class="collection">
                    <user-list v-for="user in users"
                    :key="user.id"
                    :user="user"
                    />
                </ul>
            </div>
            <div id="scroll-trigger" ref="infinitescrolltrigger">
                <div class="center" v-if="paging.nextPage">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
		</div>
		<form @submit.prevent="onSubmit">
			<div class="col m2 hide-on-small-only">
				<div class="pinned">
					<div class="row">
						{{ paging.count }}ユーザー
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
								<option value="total_favorite">総いいね数順</option>
								<option value="created">新着順</option>
								<option value="username">名前順</option>
							</select>
							<label>並び順</label>
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
									<option value="total_favorite">総いいね数順</option>
									<option value="created">新着順</option>
									<option value="username">名前順</option>
								</select>
								<label>並び順</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
    </div>
</div>
<?= $this->element('User-list') ?>
<?= $this->element('Tag-list') ?>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                users: [],
                paging: {
                    nextPage: null,
                    count: 0,
				},
				q: '',
                params: {
                    sort: 'total_favorite',
                },
                page: 1,
                loading: true,
            }
        },
        created() { 
            this.fetchUsers()
        },
        mounted() {
            this.scrollTrigger()
        },
        methods: {
            fetchUsers: async function() {
                try {
                    const {data} = await axios.get('/api/users.json', {
                        params: {
							q: this.q,
							page: this.page,
							direction: this.direction,
                            ...this.params,
                        }
					})
					console.log(data)
					this.users.push(...data.users)
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
                            this.fetchUsers()
                        }
                    });
                });
            	observer.observe(this.$refs.infinitescrolltrigger);
			},
			onSubmit() {
				if (this.loading) return 
				this.clearSearch()
				this.fetchUsers()
			},
			clearSearch() {
				this.page = 1
				this.paging = null
				this.paging =  {
					nextPage: null,
					count: 0,
				}
				this.users.splice(0)
				this.loading = true
			}
        },
        watch: {
            params: {
                handler: function () {
					if (this.loading) return 
					this.clearSearch()
                    this.fetchUsers()
                },
                deep: true
            },
        },
        computed: {
            isEmptyUsers: function() {
                return this.users.length < 1
			},
			direction: function() {
				if (this.params.sort === 'username') return 'asc'
				return 'desc'
			}
        }
    })

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.Modal.init(document.querySelectorAll('.modal'));
    });
</script>