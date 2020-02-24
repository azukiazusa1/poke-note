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

</div>
<?= $this->Form->control('body', ['class' => 'hidden', 'label' => false, 'id' => 'body']) ?>
<div id="app">
    <textarea name="body" class='hidden'>{{ value }}</textarea>
    <mavon-editor language="ja" v-model="value"/>
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
    const tootip = document.querySelectorAll('.tooltipped')
    M.Tooltip.init(tootip)

    const chip = document.querySelectorAll('.chips')
    const chipsContainer = document.getElementById('chips-container')
    const chipInstance = M.Chips.init(chip, {
        'data': [
            {tag: 'ローブシン'},
            {tag: 'ガオガエン'}
        ],
        'autocompleteOptions': {
            'data': {
                'シングル': null,
                'ダブルバトル': null
            }
        },
        'limit': 5,
        'onChipAdd': (e, chip) => {
            const chipText = chip.innerHTML.substr(0, chip.innerHTML.indexOf("<i"))
            axios.get(`/tags/add/${chipText}`)
                .then(({data}) => {
                    const hidden = document.createElement('input')
                    hidden.type = 'hidden'
                    hidden.name = 'tags[][id]'
                    hidden.value = data.id
                    chipsContainer.append(hidden)
                })
                .catch(error => {
                    console.log(error)
                })

        }
    });
});
</script>
