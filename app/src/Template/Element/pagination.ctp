<script type="text/x-template" id="pagination-template">
    <ul class="pagination">
        <li class="waves-effect" v-if="paging.prevPage" @click="$emit('paginate', paging.page - 1)">
            <a href="#!"><i class="material-icons">chevron_left</i></a>
        </li>
        <span v-for="n in paging.pageCount" :n="n">
            <li :class="{ 'active': (paging.page === n), 'waves-effect': (paging.page !== n) }">
                <a href="#!" @click="$emit('paginate', n)">{{ n }}</a>
            </li>
        </span>
        <li class="waves-effect" v-if="paging.nextPage" @click="$emit('paginate', paging.page + 1)">
            <a href="#!"><i class="material-icons">chevron_right</i></a>
        </li>
    </ul>
</script>
<script>
Vue.component('pagination', {
        props: ['paging'],
        template: '#pagination-template'
    })
</script>