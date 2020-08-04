<?php
//CSRF 偽物のCSRF.php 違うページに飛ばされたりパスワードを抜かれたりする
//sessionを使って対策する 連想配列として保存される
session_start();

require 'validation.php';

//クリックジャッキング対策 このheader関数で無効化
header('X-FRAME-OPTIONS:DENY');

//XSS対策 JavaScript無効化関数
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


echo '<pre>';
var_dump($_POST);
echo '</pre>';

//0:入力 1:確認 2:完了
$pageFlag = 0;
$error = validation($_POST);

if(!empty($_POST['btn_confirm']) && empty($error)){
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
<?php //合言葉の設定
if(!isset($_SESSION['csrfToken'])){
    $csrfToken = bin2hex(random_bytes(32));//16進数に変換してsecureな暗号作成
    $_SESSION['csrfToken'] = $csrfToken;//セッションに値(作成された暗号)を保存
}
$token = $_SESSION['csrfToken'];//セッション情報の取得

?>

<?php if(!empty($_POST['btn_confirm']) && !empty($error)) : ?>
<ul>
<?php foreach($error as $value) : ?>
<li><?php echo $value ; ?></li>
<?php endforeach ;?>
</ul>
<?php endif ;?>

<form method="POST" action="form.php">
氏名
<input type="text" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
<br>
メールアドレス
<input type="email" name="email" value="<?php echo h($_POST['email']) ; ?>">
<br>
ホームページ
<input type="url" name="url" value="<?php echo h($_POST['url']) ; ?>">
<br>
性別
<input type="radio" name="gender" value="0">男性
<input type="radio" name="gender" value="1">女性
<input type="radio" name="gender" value="2">その他
<br>
年齢
<select name="age">
    <option value="">選択してください</option>
    <option value="1">～19歳</option>
    <option value="2">20歳～29歳</option>
    <option value="3">30歳～39歳</option>
    <option value="4">40歳～49歳</option>
    <option value="5">50歳～59歳</option>
    <option value="6">60歳～</option>
</select>
<br>
お問い合わせ内容
<textarea name="contact" value="<?php echo h($_POST['contact']) ; ?>"></textarea>
<br>
<input type="checkbox" name="caution" value="1">注意事項にチェックする
<br>

<input type="submit" name="btn_confirm" value="確認する">
<input type="hidden" name="csrf" value="<?php echo $token; ?>">
</form>
<?php endif; ?>


<!-- 確認画面 -->
<?php if($pageFlag === 1) : ?>
<!-- 入力された情報とCSRFの情報が合っているか -->
<?php if($_POST['csrf'] === $_SESSION['csrfToken']) : ?>

<form method="POST" action="form.php">
名前
<?php echo h($_POST['your_name']) ; ?>
<br>
メールアドレス
<?php echo h($_POST['email']) ; ?>
<br>
ホームページ
<?php echo h($_POST['url']) ; ?>
<br>
性別
<?php if($_POST['gender'] === '0'){echo '男性';}
      if($_POST['gender'] === '1'){echo '女性';}
      if($_POST['gender'] === '2'){echo 'その他';}
?>
<br>
年齢
<?php if($_POST['age'] === '1'){echo '～19歳';}
      if($_POST['age'] === '2'){echo '20歳～29歳';}
      if($_POST['age'] === '3'){echo '30歳～39歳';}
      if($_POST['age'] === '4'){echo '40歳～49歳';}
      if($_POST['age'] === '5'){echo '50歳～59歳';}
      if($_POST['age'] === '6'){echo '60歳～';}
?>
<br>
お問い合わせ内容
<?php echo h($_POST['contact']) ; ?>
<br>
<input type="submit" name="back" value="戻る">
<input type="submit" name="btn_submit" value="送信する">
<input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
<input type="hidden" name="email" value="<?php echo h($_POST['email']) ; ?>">
<input type="hidden" name="url" value="<?php echo h($_POST['url']) ; ?>">
<input type="hidden" name="gender" value="<?php echo h($_POST['gender']) ; ?>">
<input type="hidden" name="age" value="<?php echo h($_POST['age']) ; ?>">
<input type="hidden" name="contact" value="<?php echo h($_POST['contact']) ; ?>">
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