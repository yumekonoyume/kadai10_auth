<?php
//XSS対応（ echo＝表示する場所で使用！それ以外はNG ） サニタライジングする
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続関数：db_conn()
function db_conn()
{
    try {
        $db_name = "dotslab_member_db";    //データベース名
        $db_id   = "dotslab";      //アカウント名
        $db_pw   = "yumekonoyumeDotsLab123";          
        $db_host = "mysql80.dotslab.sakura.ne.jp"; //DBホスト
        return new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}




//SQLエラー関数：sql_error($stmt)
function sql_error($stmt)
{
    $error = $stmt->errorInfo();
    exit("SQLError:" . $error[2]);
}



//リダイレクト関数: redirect($file_name)
function redirect($file_name)
{
    header("Location: $file_name");
    exit();
}


//SessionCheck
function sschk()
{
    if (!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"] != session_id()) {
        exit("Login Error");
    } else {
        session_regenerate_id(true);  //SESSION KEYを入れ替える！セキュリティアップ
        $_SESSION["chk_ssid"] = session_id();
    }
}




// 画像アップロード関数
function upload_image($file, $upload_dir = "uploads/", $max_size = 5000000)
{
    // 画像ファイルが存在し、エラーがないかをチェック
    if (isset($file) && $file["error"] == 0) {
        // ユニークなファイル名を作成
        $filename = uniqid() . "_" . basename($file["name"]);
        $uploaded_file = $upload_dir . $filename;

        // 画像ファイルかどうかをチェック
        $imageFileType = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);

        // 画像であることを確認
        if ($check !== false) {
            // ファイルサイズが最大サイズ以下かをチェック
            if ($file["size"] <= $max_size) {
                // アップロード処理
                if (move_uploaded_file($file["tmp_name"], $uploaded_file)) {
                    echo "画像アップロード成功: " . $uploaded_file . "<br>"; // デバッグ用の出力
                    return $uploaded_file; // アップロード成功で画像パスを返す
                } else {
                    echo "画像のアップロードに失敗しました。<br>";
                    exit();
                }
            } else {
                echo "画像ファイルのサイズが大きすぎます（5MBまで）。<br>";
                exit();
            }
        } else {
            echo "アップロードされたファイルは画像ではありません。<br>";
            exit();
        }
    } else {
        echo "ファイルが存在しないか、エラーが発生しています。<br>"; // デバッグ用の出力
    }
    return null; // 画像がアップロードされていない場合は null を返す
}
