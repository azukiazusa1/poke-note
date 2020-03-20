<script type="text/x-template" id="article-template">
    <li class="collection-item avatar">
        <a :href="userUrl"><img class="circle responsive-img" :src="userImg" /></a>
        <a :href="articleUrl" class="title">{{ article.title }}</a><br>
        <div class="grey-text TagList">
            <i class="tiny material-icons grey-text">local_offer</i>
            <tag-list v-for="tag in article.tags" :key="tag.id" :tag="tag" class="tag" />
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
<script>
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
</script>