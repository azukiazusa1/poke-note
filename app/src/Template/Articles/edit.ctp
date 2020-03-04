<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<?= $this->Form->create($article, [
    'v-on:submit' => 'onSubmit'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'class' => 'validate', 'id' => 'title']) ?>
<div class="chips chips-autocomplete"></div>
<div id="chips-container">
    <?php if (isset($article->tags) && count($article->tags) > 0): ?>
        <?php foreach ($article->tags as $tag): ?>
            <?= $this->Form->hidden('tags[][id]', ['value' => $tag->id, 'id' => $tag->title, 'class' => 'tags']) ?>
        <?php endforeach ?>
    <?php endif ?>
</div>
<?= $this->Form->control('body', ['class' => 'hide', 'label' => false, 'id' => 'body']) ?>
<div id="app">
</div>
<script type="text/x-template" id="my-component">
    <div>
        <textarea name="body" class='hide'>{{ value }}</textarea>
        <mavon-editor 
        language="ja" 
        v-model="value"
        ref=md
        @imgAdd="$imgAdd"
        @change="$save"
        @save="$save"
        />
    </div>
</script>
<div>
    <span class="bold">＊記事は自動で保存されます</span>
    <span class="right">
            <input type="hidden" value="0" name="published" />
            <?php $checked = (($article->published) ? 'checked' : false) ?>
            <span class="switch">
                <label>
                    <input type="checkbox" name="published" <?= $checked ?> value="1" />
                    <span class="lever"></span>
                    記事を公開する
                </label>
            </span>
        <?= $this->Form->button('投稿', ['class' => 'btn blue btn-large']) ?>
    </span>
</div>
<?= $this->Form->end() ?>
<script>
const articleId = '<?= $article->id?>';
Vue.use(window['MavonEditor'])

new Vue({
   el: '#app',
   data() {
        return { 
            value: document.getElementById('body').value,
            message: '',
        }
    },
    methods: {
        $imgAdd(pos, $file) {
            const formdata = new FormData()
            formdata.append('image', $file)
            axios({
                url: `/api/articles/${articleId}/files.json`,
                method: 'post',
                data: formdata,
                headers: { 'Content-Type': 'multipart/form-data' },
            }).then(({data}) => {                
                this.$refs.md.$img2Url(pos, data.filename);
            }).then(err => {
                console.log(err)
            })
        },
        $save(body) {
            const data = {
                body: body,
                title: document.getElementById('title').value
            }
            axios.put(`/api/articles/${articleId}.json`, data)
        }
    },
    template: '#my-component'
})

document.addEventListener('DOMContentLoaded', function() {
    const chip = document.querySelectorAll('.chips')
    const chipsContainer = document.getElementById('chips-container')
    const tags = chipsContainer.querySelectorAll('.tags')
    let tagsData = [];
    tags.forEach(tag => {
        tagsData.push({tag: tag.id})
    });
    (async () => {
        const autocompleteData = {}
        await axios.get('/api/tags.json')
            .then(({data}) => {
                data.tags.forEach(tag => {
                    autocompleteData[tag.title] = null
                })
            }).catch(error => {
                console.log(error)
            })

        const chipInstance = M.Chips.init(chip, {
            'data': tagsData,
            'autocompleteOptions': {
                'data': autocompleteData
            },
            'limit': 5,
            'onChipAdd': (e, chip) => {
                const chipText = chip.innerHTML.substr(0, chip.innerHTML.indexOf("<i"))
                axios.post('/api/tags.json', {'data': chipText})
                    .then(({data}) => {
                        const hidden = document.createElement('input')
                        hidden.type = 'hidden'
                        hidden.name = 'tags[][id]'
                        hidden.value = data.tag.id
                        hidden.id = data.tag.title
                        hidden.class = 'tags'
                        chipsContainer.append(hidden)
                        axios.post(`/api/articles/${articleId}/tags.json`, {'data': data.tag.id})
                    })
                    .catch(error => {
                        console.log(error)
                    })

            },
            'onChipDelete': (e, chip) => {
                const chipText = chip.innerHTML.substr(0, chip.innerHTML.indexOf("<i"))
                const deleteTag = document.getElementById(chipText)
                axios.delete(`/api/articles/${articleId}/tags/${deleteTag.value}.json`)
                deleteTag.remove()
            }
        });
    })()
});
</script>