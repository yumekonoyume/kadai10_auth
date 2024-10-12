<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <link rel="stylesheet" href="css/login_admin.css"> <!-- CSSファイルへのリンク -->
</head>

<body>


    <div class="login-container">
        <h2>管理者ログイン</h2> <!-- ログイン画面の見出し -->
        <form name="form1" action="login_act.php" method="post">
            <label for="lid">ID</label>
            <input type="text" name="lid" id="lid">

            <label for="lpw">パスワード</label>
            <input type="password" name="lpw" id="lpw">

            <input type="submit" value="ログイン">
        </form>
    </div>

</body>

</html>
