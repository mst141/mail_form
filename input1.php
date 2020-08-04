<?php

//GET,POSTはスーパーグローバル変数
//連想配列で値を受け取る -> inputタグのnameがキーになってる
echo '<pre>';
var_dump($_GET);
echo '</pre>';

?>



<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>mail_form</title>
</head>
<body>

<!-- method:GET/POST action:処理をするファイル -->
<form method="GET" action="input1.php">

名前
<input type="text" name="name">
<br>
<input type="checkbox" name="sports[]" value="野球">野球
<input type="checkbox" name="sports[]" value="サッカー">サッカー
<input type="checkbox" name="sports[]" value="バスケ">バスケ
<input type="submit" value="送信">
</form>

</body>
</html>