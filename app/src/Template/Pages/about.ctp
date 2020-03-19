<?php $this->assign('title', 'Pnote!とは？ | PNote!') ?>
<div class="container">
    <div class="row center">
        <div class="col s12">
            <h1>PNote!とは？</h1>
            <p>PNote!はポケモン好きのための記事投稿サイトです。誰でも簡単に自由に投稿することができます！</p>
        </div>
    </div>
    <div class="row center">
        <div class="col s12">
            <h2>PNote!のコンセプト</h2>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <h3 class="center">ポケモンに関する情報が、集まる場所にする。</h3>
            <p>
                あなたがポケモンをプレイしていて知りたいことはなんですか？結果を残している構築、よりよいポケモンの育成論、レイドバトルの情報、攻略情報...たくさんのことがあると思います。
                そんな有益な情報を、みんなで共有してまとめられるサイトを目指しました。興味のあるキーワード、タグ、ユーザーを利用してきっとあなたの知りたい情報を見つけられるはずです。
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <h3 class="center">誰でも簡単に情報を投稿できるようにする</h3>
            <p>
                ポケモンの情報をまとめたサイトは多くありますが、そのサイトに記事を投稿することができるのは限られた人にしかすぎません。当サイトは、誰でも簡単に自由に投稿することができることを
                目指しました。簡単なユーザー登録をするだけで、誰でも記事を投稿することができます。また、記事の編集にはマークダウン記法を採用しており、驚くほど簡単に記述することができます。
                さあ、どんどん情報を発信していきましょう。
            </p>
            <hr class="list-divider">
        </div>
    </div>
    <div class="row">
        <div class="col s12 center">
            <?= $this->Html->link('さっそくユーザー登録をする', 
                ['controller' => 'Users', 'action' => 'signup'],
                ['class' => 'btn red accent-2 btn-large']
            )?>
        </div>
    </div>
</div>