<?php
// 1. IDの取得
$id = $_GET["id"];

include("funcs.php");
$pdo = db_conn();

// 2．データ取得SQL作成
$sql = "SELECT * FROM profiles WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  // Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

// 3．データ表示
if ($status == false) {
    sql_error($stmt);
}

// 取得データを変数に格納
$v = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ＭＹプロフィール修正</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/form-update.css" rel="stylesheet">
    <style>
        ::placeholder {
            color: #a9a9a9; /* 薄いグレー */
            font-weight: normal; /* 太字を解除 */
        }
        .btn-action {
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="select.php">◀ メンバー 一覧へ</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <div class="form-container">
        <form method="POST" action="update.php" enctype="multipart/form-data">
            <fieldset>
                <legend>修正箇所を上書きしてください😌</legend>

                <label>名前：</label>
                <input type="text" name="name" value="<?= h($v['name']); ?>" placeholder="漢字フルネーム" required><br>

                <label>ニックネーム：</label>
                <input type="text" name="nickname" value="<?= h($v['nickname']); ?>" placeholder="ニックネームや呼ばれたい名前"><br>

                <label>フリガナ：</label>
                <input type="text" name="furigana" value="<?= h($v['furigana']); ?>" placeholder="全角カタカナ、スペースなし" required><br>

                <label>Email：</label>
                <input type="email" name="email" value="<?= h($v['email']); ?>" placeholder="例：example@example.com" required><br>

                <label>自己紹介文：</label>
                <textarea name="introduction" rows="4" placeholder="現在取り組んでいることやお仕事内容など" required><?= h($v['introduction']); ?></textarea><br>

                <label>興味・特技：</label>
                <textarea name="hobby_skill" rows="2" placeholder="ハマっていることや得意分野など"><?= h($v['hobby_skill']); ?></textarea><br>

                <label>一言メッセージ：</label>
                <textarea name="message" rows="2" placeholder="みんなへのお知らせや聞きたいことなど"><?= h($v['message']); ?></textarea><br>

                <!-- プロフィール画像 -->
                <label>プロフィール画像：</label>
                <input type="file" name="profile_image"><br>

                <?php if ($v['profile_image']): ?>
                    <p>現在の画像：</p>
                    <img src="<?= h($v['profile_image']); ?>" alt="プロフィール画像" style="max-width: 200px;"><br>
                <?php else: ?>
                    <p>画像は登録されていません。</p>
                <?php endif; ?>

                <input type="hidden" name="id" value="<?= h($v['id']); ?>">

                <div class="btn-action">
                    <button type="submit" class="btn btn-warning">更新する</button>
                    <!-- <a href="delete.php?id=<?= h($v['id']); ?>" class="btn btn-danger">削除する</a> -->
                </div>
            </fieldset>
        </form>
    </div>
    <!-- Main[End] -->

</body>

</html>