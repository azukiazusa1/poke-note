<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->script('../node_modules/axios/dist/axios.min.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<?= $this->Form->create($article, [
    'v-on:submit' => 'onSubmit'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'class' => 'validate']) ?>
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
    <textarea name="body" class='hide'>{{ value }}</textarea>
    <mavon-editor 
    language="ja" 
    v-model="value"
    />
</div>
<div class="right">
    <label>
        <input type="hidden" value="0" name="published" />
        <?php $checked = (($this->request->getData('published')) ? 'checked' : false) ?>
        <input type="checkbox" class="filled-in" name="published" <?= $checked ?> value="1" />
        <span class="tooltipped" data-position="top" data-tooltip="下書きにチェックを付けた場合、公開されません。">下書き</span>
    </label>
    <?= $this->Form->button('投稿', ['class' => 'btn blue btn-large']) ?>
</div>
<?= $this->Form->end() ?>
<script>

Vue.use(window['MavonEditor'])

new Vue({
   el: '#app',
   data() {
        return { 
            value: document.getElementById('body').value,
            message: '',
        }
    },
})

document.addEventListener('DOMContentLoaded', function() {
    const articleId = '<?= $article->id?>';
    const tootip = document.querySelectorAll('.tooltipped')
    M.Tooltip.init(tootip)

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
                axios.post('/api/tags/.json', {'data': chipText})
                    .then(({data}) => {
                        const hidden = document.createElement('input')
                        hidden.type = 'hidden'
                        hidden.name = 'tags[][id]'
                        hidden.value = data.tag.id
                        hidden.id = data.tag.title
                        hidden.class = 'tags'
                        chipsContainer.append(hidden)
                    })
                    .catch(error => {
                        console.log(error)
                    })

            },
            'onChipDelete': (e, chip) => {
                const chipText = chip.innerHTML.substr(0, chip.innerHTML.indexOf("<i"))
                const deleteTag = document.getElementById(chipText)
                axios.delete(`/api/tags/${deleteTag.value}.json`, {'data': articleId})
                deleteTag.remove()
            }
        });
    })()
});
</script>