<?php
// すでにセッションが開始されていない場合のみ、セッション開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 管理者がログインしているかどうかを確認（$_SESSION['kanri_flg']が設定されている場合）
$is_admin = isset($_SESSION['kanri_flg']) && $_SESSION['kanri_flg'] == 1;

//関数群の読み込み
include("funcs.php");

//データベース接続
$pdo = db_conn();

// 並び替えの条件を決める
$order_by = "updated_at DESC"; // 初期は最新更新順
if (isset($_GET['order']) && $_GET['order'] === 'furigana') {
    $order_by = "furigana ASC"; // フリガナ順に変更
}

// 2．データ登録SQL作成 (updated_at でソートまたはフリガナ順)
$sql = "SELECT * FROM profiles ORDER BY $order_by";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// 3．データ表示
$values = []; // 初期化
if ($status == false) {
    sql_error($stmt);
} else {
    $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 全データ取得後の表示処理
$json = json_encode($values, JSON_UNESCAPED_UNICODE);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>メンバー 一覧</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> <!-- 共通スタイルの読み込み -->

</head>

<body id="main">

    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">◀ Myプロフィール登録へ</a>
                </div>
                <div class="navbar-right">
                    <?php if ($is_admin): ?>
                        <!-- ログアウトリンク -->
                        <a href="logout.php" class="navbar-link">管理画面ログアウト</a>
                    <?php else: ?>
                        <!-- 管理者ログインリンク -->
                        <a href="login.php" class="navbar-link">管理者ログイン</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <div class="container">
        <div class="sort-buttons">
            <!-- ソートボタン: フリガナ順と登録順 -->
            <a href="select.php" class="btn btn-primary btn-action">登録順</a>
            <a href="select.php?order=furigana" class="btn btn-primary btn-action">フリガナ順</a>
        </div>

        <!-- メンバー一覧表示 -->
        <?php if (!empty($values)) { ?>
            <?php foreach ($values as $v) { ?>
                <div class="member-card">
                    <div>
                        <?php if ($v["profile_image"]) { ?>
                            <img src="<?= h($v["profile_image"]) ?>" alt="プロフィール画像">
                        <?php } else { ?>
                            <img src="default-profile.png" alt="画像なし"> <!-- デフォルト画像 -->
                        <?php } ?>
                    </div>
                    <div class="member-details">
                        <!-- 名前とフリガナの表示 -->
                        <div class="member-header">
                            <h3><?= h($v["name"]) ?></h3>
                            <p class="furigana"><?= h($v["furigana"]) ?></p> <!-- フリガナを小さく表示 -->
                        </div>

                        <!-- 自己紹介とその他の情報 -->
                        <div class="member-bio">
                            <p class="member-bio-heading">ニックネーム</p>
                            <p class="nickname"><?= h($v["nickname"]) ?></p>

                            <p class="member-bio-heading">自己紹介</p>
                            <p><?= h($v["introduction"]) ?></p>

                            <p class="member-bio-heading">興味・特技</p>
                            <p><?= h($v["hobby_skill"]) ?></p>

                            <p class="member-bio-heading">メッセージ</p>
                            <p><?= h($v["message"]) ?></p>
                        </div>

                        <!-- ボタン -->
                        <div class="member-footer">
                            <a href="detail.php?id=<?= h($v["id"]) ?>" class="btn btn-warning btn-action">修正する</a>

                            <!-- 管理者のみ削除ボタンを表示 -->
                            <?php if ($is_admin): ?>
                                <a href="delete.php?id=<?= h($v["id"]) ?>" class="btn btn-danger btn-action" onclick="return confirm('本当に削除しますか？')">削除する</a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>メンバーがまだ登録されていません。</p>
        <?php } ?>
    </div>
    <!-- Main[End] -->

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script>
        $(document).ready(function() {
            // ページ全体をフェードインさせる
            $('.member-card').each(function(index) {
                $(this).delay(200 * index).queue(function(next) {
                    $(this).addClass('visible');
                    next();
                });
            });
        });
    </script>

</body>

</html>