<?php

//クリックジャッキング対策 このheader関数で無効化
header('X-FRAME-OPTIONS:DENY');

//XSS対策 JavaScript無効化関数
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';

//0:入力 1:確認 2:完了
$pageFlag = 0;

if(!empty($_POST['btn_confirm'])){
    $pageFlag = 1;
}

if(!empty($_POST['btn_submit'])){
    $pageFlag = 2;
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mail_form</title>
</head>
<body>

<!-- 入力画面 -->
<?php if($pageFlag === 0) : ?>
<form method="POST" action="xss.php">
名前
<input type="text" name="your_name"　value="<?php echo h($_POST['your_name']) ; ?>">
<br>
メールアドレス
<input type="text" name="email" value="<?php echo h($_POST['email']) ; ?>">
<br>
<input type="submit" name="btn_confirm" value="確認する">
</form>
<?php endif; ?>


<!-- 確認画面 -->
<?php if($pageFlag === 1) : ?>
<form method="POST" action="xss.php">
名前
<?php echo h($_POST['your_name']) ; ?>
<br>
メールアドレス
<?php echo h($_POST['email']) ; ?>
<br>
<input type="submit" name="back" value="戻る">
<input type="submit" name="btn_submit" value="送信する">
<input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
<input type="hidden" name="email" value="<?php echo h($_POST['email']) ; ?>">
</form>
<?php endif; ?>


<!-- 完了画面 -->
<?php if($pageFlag === 2) : ?>
送信が完了しました。
<?php endif; ?>


</body>
</html>