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
<script>
Vue.component('comments-list', {
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
</script>