<?php
//CSRF 偽物のCSRF.php 違うページに飛ばされたりパスワードを抜かれたりする
//sessionを使って対策する 連想配列として保存される
session_start();

//php.ini エラーを表示
ini_set("display_errors", 1);
error_reporting(E_ALL);

//クリックジャッキング対策 このheader関数で無効化
header('X-FRAME-OPTIONS:DENY');

//XSS対策 JavaScript無効化関数
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

echo '<pre>';
var_dump($_SESSION);
print_r($_POST);//POSTの値を表示
echo '</pre>';

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
<?php //合言葉作成
 if(!isset($_SESSION['csrfToken'])){
  $csrfToken = bin2hex(random_bytes(32)); //16進数に変換してsecureな暗号作成
  $_SESSION['csrfToken'] = $csrfToken; //セッションに値(作成された暗号)を保存
 }
$token = $_SESSION['csrfToken']; //セッション情報の取得

?>

<form method="POST" action="csrf.php">
名前
<input type="text" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
<br>
メールアドレス
<input type="email" name="email" value="<?php echo h($_POST['email']) ; ?>">
<br>
<input type="submit" name="btn_confirm" value="確認する">

<!-- POST通信は1回で切れる バックグラウンドで値を持たせる -->
<input type="hidden" name="csrf" value="<?php echo $token ; ?>">
</form>

<?php endif; ?>


<!-- 確認画面 -->
<?php if($pageFlag === 1) : ?>
<!-- 入力された情報とcsrfの情報が合っているか -->
<?php if($_POST['csrf'] === $_SESSION['csrfToken']) : ?>

<form method="POST" action="csrf.php">
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
<input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']) ; ?>">

</form>

<?php endif; ?>
<?php endif; ?>


<!-- 完了画面 -->
<?php if($pageFlag === 2) : ?>
<?php if($_POST['csrf'] === $_SESSION['csrfToken']) : ?>
送信が完了しました。

<!-- 合言葉の削除 -->
<?php unset($_SESSION['csrfToken']); ?>

<?php endif; ?>
<?php endif; ?>

</body>
</html>