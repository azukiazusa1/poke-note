<?= h($username) ?>さん。
ご利用のパスワードがリセットされました。
お客様がこの変更を行っていない場合、または他人が不正にアカウントにアクセスしていると思われる場合は、
<?= $this->Url->build(["controller" => "PasswordForgot","action" => "index"], true) ?>

にアクセスしてただちにパスワードを変更してください。

今後ともよろしくお願いいたします。