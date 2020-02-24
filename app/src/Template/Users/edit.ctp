<div class="container">
    <div class="row">
        <div class="col m3">
            <div class="collection">
                <?= $this->Html->link('プロフィール編集', ['controller' => 'Users', 'action' => 'edit'],
                    ['class' => 'collection-item red accent-2 white-text']) ?>
                <a href="#!" class="collection-item black-text">Alvin</a>
                <a href="#!" class="collection-item black-text">Alvin</a>
                <a href="#!" class="collection-item black-text">Alvin</a>
            </div>
        </div>
        <div class="col m9">
            <div class="card">
                <div class="card-content">
                    <span class="card-title"><h4>プロフィール編集 / @<?= h($user->username) ?></p></h4></span>
                    <p><?= $this->Html->image($user->image, [
                            'alt' => 'user',
                            'class' => 'responsive-img circle icon-image',
                        ])?>
                    <?= $this->Form->create($user, ['type' => 'file']) ?>
                    <div class="file-field input-field">
                        <div class="btn red accent-2">
                            <span>プロフィール画像</span>
                            <input type="file" name="image_file">
                        </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" />
                    </div>
                    </div>
                    <?= $this->Form->control('nickname', ['label' => '名前', 'maxlength' => 32, 'data-length' => "32", "class" => "text"]) ?>
                    <label for="desctiption">自己紹介</label>
                    <?= $this->Form->textarea('description', ['maxlength' => 255, 'class' => 'materialize-textarea text', 'data-length' => "255", 'id' => 'description']) ?>
                    <?= $this->Form->button('更新する', ['class' => 'btn blue btn-large']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const elems = document.querySelectorAll('.text');
        M.CharacterCounter.init(elems)
    });
</script>