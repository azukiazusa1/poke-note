<?= $this->Html->script('../node_modules/vue/dist/vue.js') ?>
<?= $this->Html->script('../node_modules/mavon-editor/dist/mavon-editor.js') ?>
<?= $this->Html->css('../node_modules/mavon-editor/dist/css/index.css') ?>
<?= $this->Form->create($article, [
    'v-on:submit' => 'onSubmit'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル']) ?>
<?= $this->Form->control('tag', ['label' => 'タグ']) ?>
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
    const elems = document.querySelectorAll('.tooltipped');
    const instances = M.Tooltip.init(elems);
  });
</script>
