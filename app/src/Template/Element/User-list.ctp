<script type="text/x-template" id="user-template">
    <li class="collection-item avatar">
        <a :href=userUrl><img :src=userImg :alt=userUrl class="circle"></a>
        <a class="title" :href=userUrl>@{{ user.username }}</a>
        <span>{{ user.nickname }}</span>
        <span class="right">
            <i class="tiny material-icons red-text text-accent-2">thumb_up</i>
            {{ user.total_favorite }}
        </span>
        <div>{{ user.description }}</div>
    </li>   
</script>
<script>
Vue.component('user-list', {
    props: ['user'],
    data() {
        return {
            userImg: `${this.user.image}`,
            userUrl: `/users/${this.user.username}`,
        }
    },
    template: '#user-template'
})
</script>
