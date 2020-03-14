<script>
Vue.component('Tag-list', {
        props: ['tag'],
        data() {
            return {
                tagUrl: `/tags/${this.tag.title}`
            }
        },
        template: `
            <span>
                <a :href="tagUrl" class="tag-color">{{ tag.title }}</a>
            </span>
        `
    })
</script>