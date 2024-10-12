<?php
// funcs.php の読み込み
include("funcs.php");

session_start(); // セッション開始

// 1. POSTデータ取得
$name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
$nickname = htmlspecialchars($_POST["nickname"], ENT_QUOTES, 'UTF-8');
$furigana = htmlspecialchars($_POST["furigana"], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
$introduction = htmlspecialchars($_POST["introduction"], ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($_POST["message"], ENT_QUOTES, 'UTF-8');
$hobby_skill = isset($_POST["hobby_skill"]) ? htmlspecialchars($_POST["hobby_skill"], ENT_QUOTES, 'UTF-8') : null;
$consent = isset($_POST["consent"]) ? 1 : 0;

// 画像パスをセッションから取得
$profile_image = isset($_POST["uploaded_image"]) ? $_POST["uploaded_image"] : null;

// 2. DB接続
$pdo = db_conn();

// 3. データ登録SQL作成
$stmt = $pdo->prepare(
    "INSERT INTO profiles(name, nickname, furigana, email, introduction, message, hobby_skill, profile_image, consent, created_at)
     VALUES(:name, :nickname, :furigana, :email, :introduction, :message, :hobby_skill, :profile_image, :consent, sysdate())"
);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
$stmt->bindValue(':furigana', $furigana, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':introduction', $introduction, PDO::PARAM_STR);
$stmt->bindValue(':message', $message, PDO::PARAM_STR);
$stmt->bindValue(':hobby_skill', $hobby_skill, PDO::PARAM_STR);
$stmt->bindValue(':profile_image', $profile_image, PDO::PARAM_STR); // セッションから取得した画像パスをバインド
$stmt->bindValue(':consent', $consent, PDO::PARAM_INT);

$status = $stmt->execute(); // 実行

// 4. データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect("select.php");
}
?>
