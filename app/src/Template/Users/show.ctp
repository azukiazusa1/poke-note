<div class="container">
    <div class="row">
        <div class="col m8 s12">
            <div class="card">
                <div class="card-image">
                    <?= $this->Html->image($user->image, [
                        'alt' => 'Author',
                        'class' => 'materialboxed responsive-img circle mypage-img',
                    ])?>
                    <span class="card-title">@<?= h($user->username) ?></span>
                </div>
                <span class="card-title">
                    <?= h($user->nickname) ?>
                    <?php if (isset($login_user->id)): ?>
                        <?php if ($user->id === $login_user->id) : ?>
                            <p><?= $this->Html->link('プロフィールを編集', ['controller' => 'Users', 'action' => 'edit'], ['class' => 'btn'])?></p>
                        <?php else: ?>
                            <a href="#" class="btn right rounded">フォロー</a>
                        <?php endif ?>
                    <?php endif ?>
                </span>
                <div class="card-content">
                    <p><?= h($user->description) ?></p>
                </div>
                <div class="card-action">
                    <i class="tiny material-icons red-text text-accent-2">thumb_up</i><?= h($favorite_count) ?><br>
                    <span class="bold">フォロー</span><a class="modal-trigger" href="#modal-follow">100</a>
                    <div id="modal-follow" class="modal">
                        <div class="modal-content">
                            <h4>Modal Header</h4>
                            <p>A bunch of text</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
                        </div>
                    </div>
                    <span class="bold">フォロワー</span><a class="modal-trigger" href="#modal-follower">100</a>
                    <div id="modal-follower" class="modal">
                        <div class="modal-content">
                            <h4>Modal Header</h4>
                            <p>A bunch of text</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
                            </div>
                        </div>
                    </div>
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                        <li class="tab"><a href="#test4">投稿した記事</a></li>
                        <li class="tab"><a class="active" href="#test5">いいねした記事</a></li>
                        <li class="tab"><a href="#test6">コメント</a></li>
                    </ul>
                    </div>
                    <div class="card-content grey lighten-4">
                    <div id="test4">Test 1</div>
                    <div id="test5">Test 2</div>
                    <div id="test6">Test 3</div>
                </div>
            </div>
        </div>
        <div class="col m4 s12">
            <ul class="collection with-header">
                <li class="collection-header"><h4>人気の記事</h4></li>
                <li class="collection-item">Alvin<span class="right"><i class="tiny material-icons red-text text-accent-2">thumb_up</i>100</span</li>
                <li class="collection-item">Alvin<span class="right"><i class="tiny material-icons red-text text-accent-2">thumb_up</i>100</span</li>
                <li class="collection-item">Alvin<span class="right"><i class="tiny material-icons red-text text-accent-2">thumb_up</i>100</span</li>
                <li class="collection-item">Alvin<span class="right"><i class="tiny material-icons red-text text-accent-2">thumb_up</i>100</span</li>
            </ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.Materialbox.init(document.querySelector('.materialboxed'));
        M.Tabs.init(document.querySelectorAll('.tabs'));
        M.Modal.init(document.querySelectorAll('.modal'));
  });
</script>