<?php

//GET,POSTはスーパーグローバル変数
//連想配列で値を受け取る -> inputタグのnameがキーになってる
echo '<pre>';
var_dump($_POST);
echo '</pre>';

//入力、確認、完了  input.php confirm.php thanks.phpに分けて書く
//input.phpにまとめて書く

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
<form method="POST" action="input2.php">
名前
<input type="text" name="your_name"　value="<?php echo $_POST['your_name'] ; ?>">
<br>
メールアドレス
<input type="text" name="email" value="<?php echo $_POST['email'] ; ?>">
<br>
<input type="submit" name="btn_confirm" value="確認する">
</form>
<?php endif; ?>


<!-- 確認画面 -->
<?php if($pageFlag === 1) : ?>
<form method="POST" action="input2.php">
名前
<?php echo $_POST['your_name'] ; ?>
<br>
メールアドレス
<?php echo $_POST['email'] ; ?>
<br>
<input type="submit" name="back" value="戻る">
<input type="submit" name="btn_submit" value="送信する">
<input type="hidden" name="your_name" value="<?php echo $_POST['your_name'] ; ?>">
<input type="hidden" name="email" value="<?php echo $_POST['email'] ; ?>">
</form>
<?php endif; ?>


<!-- 完了画面 -->
<?php if($pageFlag === 2) : ?>
送信が完了しました。
<?php endif; ?>


</body>
</html>