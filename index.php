<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>自己紹介フォーム</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">

    <style>
        ::placeholder {
            color: #a9a9a9; /* 少し薄いグレーに変更 */
            font-weight: normal; /* 太字を解除 */
        }
    </style>

    <script>
        // JavaScriptで全角カタカナチェック
        function validateFurigana() {
            const furigana = document.getElementById("furigana").value;
            const katakanaRegex = /^[゠-ヿー\s]+$/; // 全角カタカナとスペース

            if (!katakanaRegex.test(furigana)) {
                alert("フリガナは全角カタカナで入力してください。");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>

    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="select.php">◀ メンバー一覧を見る</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <?php
    session_start(); // セッションを開始して画像パスを保持

    // POSTデータが送信され、確認画面表示フラグがセットされている場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
        // フォームから送信されたデータを取得
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');
        $furigana = htmlspecialchars($_POST['furigana'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $introduction = htmlspecialchars($_POST['introduction'], ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
        $hobby_skill = htmlspecialchars($_POST['hobby_skill'], ENT_QUOTES, 'UTF-8');

        // サーバー側の全角カタカナチェック
        if (!preg_match("/^[ァ-ヶー\s]+$/u", $furigana)) { // 全角カタカナとスペースのみ
            echo "<script>alert('フリガナは全角カタカナで入力してください。');</script>";
            exit();
        }

        // 画像のアップロード処理
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            $tmp_name = $_FILES['profile_image']['tmp_name'];
            $filename = uniqid() . "_" . basename($_FILES['profile_image']['name']);
            $upload_path = $upload_dir . $filename;

            if (move_uploaded_file($tmp_name, $upload_path)) {
                // 画像パスをセッションに保存
                $_SESSION['uploaded_image'] = $upload_path;
            } else {
                echo "画像のアップロードに失敗しました。";
            }
        }

        // セッションに保存した画像パスを取得
        $uploaded_image = isset($_SESSION['uploaded_image']) ? $_SESSION['uploaded_image'] : null;
    ?>
        <!-- 確認画面 -->
        <div class="form-container">
    <h4>入力内容をご確認ください😊</h4> <!-- ここが見出し部分です -->
    <p>【名前】 <?= $name ?></p>
    <p>【フリガナ】 <?= $furigana ?></p>
    <p>【ニックネーム】 <?= $nickname ?></p>
    <p>【Email】 <?= $email ?></p>
    <p>【自己紹介文】 <?= $introduction ?></p>
    <p>【趣味・特技】 <?= $hobby_skill ?></p>
    <p>【一言メッセージ】 <?= $message ?></p>


    <?php if ($uploaded_image): ?>
        <p>【プロフィール画像】</p>
        <img src="<?= $uploaded_image ?>" alt="画像プレビュー" style="max-width: 200px;">
    <?php else: ?>
        <p><strong>画像:</strong> なし</p>
    <?php endif; ?>

    <div class="button-group">
        <form method="POST" action="insert.php" enctype="multipart/form-data">
            <input type="hidden" name="name" value="<?= $name ?>">
            <input type="hidden" name="nickname" value="<?= $nickname ?>">
            <input type="hidden" name="furigana" value="<?= $furigana ?>">
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="introduction" value="<?= $introduction ?>">
            <input type="hidden" name="message" value="<?= $message ?>">
            <input type="hidden" name="hobby_skill" value="<?= $hobby_skill ?>">
            <input type="hidden" name="uploaded_image" value="<?= $uploaded_image ?>">

            <button type="submit" formaction="index.php" name="modify" class="btn btn-warning">修正する</button>
            <button type="submit" class="btn btn-success">送信する</button>
        </form>
    </div>
</div>


    <?php
    // 修正画面
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify'])) {
        // 修正用の入力画面に戻る
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');
        $furigana = htmlspecialchars($_POST['furigana'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $introduction = htmlspecialchars($_POST['introduction'], ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
        $hobby_skill = htmlspecialchars($_POST['hobby_skill'], ENT_QUOTES, 'UTF-8');
        $uploaded_image = isset($_SESSION['uploaded_image']) ? $_SESSION['uploaded_image'] : null;
    ?>
<!-- 修正画面 -->
<form method="POST" action="index.php" enctype="multipart/form-data" onsubmit="return validateFurigana()">
    <div class="form-container">
        <fieldset>
            <legend>Myプロフィール</legend>
            <label><h3>ご入力 お願いします😌</h3></label>

            <label>名前：<input type="text" name="name" value="<?= $name ?>" placeholder="漢字フルネーム" required></label><br>

            <label>フリガナ：<input type="text" name="furigana" id="furigana" value="<?= $furigana ?>" placeholder="全角カタカナ、スペースなし" required></label><br>

            <label>ニックネーム：<input type="text" name="nickname" value="<?= $nickname ?>" placeholder="ニックネームや呼ばれたい名前"></label><br>

            <label>Email：<input type="email" name="email" value="<?= $email ?>" placeholder="例：example@example.com" required></label><br>

            <label>自己紹介文：<textarea name="introduction" rows="4" cols="40" placeholder="現在取り組んでいることやお仕事内容など" required><?= $introduction ?></textarea></label><br>

            <label>趣味・特技：<textarea name="hobby_skill" rows="2" cols="40" placeholder="ハマっていることや得意分野など"><?= $hobby_skill ?></textarea></label><br>

            <label>一言メッセージ：<textarea name="message" rows="2" cols="40" placeholder="みんなへのお知らせや聞きたいことなど"><?= $message ?></textarea></label><br>

            <!-- プロフィール画像 -->
            <label>プロフィール画像：<input type="file" name="profile_image"></label><br>

            <?php if ($uploaded_image): ?>
                <img src="<?= $uploaded_image ?>" alt="画像プレビュー" style="max-width: 200px;"><br>
            <?php endif; ?>

            <button type="submit" name="confirm" class="btn btn-primary">確認画面へ</button>
        </fieldset>
    </div>
</form>


    <?php
    } else {
        // 最初の入力画面が表示されるようにする
    ?>
        <!-- 初期の入力フォーム -->
        <form method="POST" action="index.php" enctype="multipart/form-data" onsubmit="return validateFurigana()">
            <div class="form-container">
                <fieldset>
                    <legend>Myプロフィール</legend>
                    <label><h3>ご入力 お願いします😌</h3></label>
                    <label>名前：<input type="text" name="name" placeholder="漢字フルネーム" required></label><br>
                    <label>フリガナ：<input type="text" name="furigana" id="furigana" placeholder="全角カタカナ、スペースなし" required></label><br>
                    <label>ニックネーム：<input type="text" name="nickname" placeholder="ニックネームや呼ばれたい名前"></label><br>
                    <label>Email：<input type="email" name="email" placeholder="例：example@example.com" required></label><br>
                    <label>自己紹介文：<textarea name="introduction" rows="4" cols="40" placeholder="現在取り組んでいることやお仕事内容など" required></textarea></label><br>
                    <label>趣味・特技：<textarea name="hobby_skill" rows="2" cols="40" placeholder="ハマっていることや得意分野など"></textarea></label><br>
                    <label>一言メッセージ：<textarea name="message" rows="2" cols="40" placeholder="みんなへのお知らせや聞きたいことなど"></textarea></label><br>
                    <label>プロフィール画像：<input type="file" name="profile_image"></label><br>
                    <button type="submit" name="confirm" class="btn btn-primary">確認画面へ</button>
                </fieldset>
            </div>
        </form>
    <?php
    }
    ?>

</body>

</html>