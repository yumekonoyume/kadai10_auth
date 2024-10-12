<?php
// funcs.php の読み込み
include("funcs.php");

// 1. POSTデータ取得
$id = $_POST["id"];
$name = $_POST["name"];
$nickname = $_POST["nickname"]; // ニックネーム
$furigana = $_POST["furigana"];
$email = $_POST["email"];
$introduction = $_POST["introduction"];
$message = $_POST["message"]; // 一言メッセージ
$hobby_skill = isset($_POST["hobby_skill"]) ? $_POST["hobby_skill"] : null;
$consent = isset($_POST["consent"]) ? 1 : 0; // チェックボックスがある場合

// 画像アップロード処理
$profile_image = upload_image($_FILES["profile_image"]); // 関数を使用

// 2. DB接続
$pdo = db_conn();

// 3. SQL作成（画像がアップロードされていない場合は画像パスの更新を行わない）
if ($profile_image) {
    $sql = "UPDATE profiles SET name=:name, nickname=:nickname, furigana=:furigana, email=:email, introduction=:introduction, message=:message, hobby_skill=:hobby_skill, profile_image=:profile_image, consent=:consent WHERE id=:id";
} else {
    $sql = "UPDATE profiles SET name=:name, nickname=:nickname, furigana=:furigana, email=:email, introduction=:introduction, message=:message, hobby_skill=:hobby_skill, consent=:consent WHERE id=:id";
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR); // ニックネーム
$stmt->bindValue(':furigana', $furigana, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':introduction', $introduction, PDO::PARAM_STR);
$stmt->bindValue(':message', $message, PDO::PARAM_STR); // 一言メッセージ
$stmt->bindValue(':hobby_skill', $hobby_skill, PDO::PARAM_STR);
if ($profile_image) {
    $stmt->bindValue(':profile_image', $profile_image, PDO::PARAM_STR); // プロフィール画像のパスを更新
}
$stmt->bindValue(':consent', $consent, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

$status = $stmt->execute(); // 実行

// 4. データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect("select.php"); // 更新後に一覧ページへリダイレクト
}
?>
