<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
    <style>
        h2{
            padding-left: 30px;
        }
        .forms{
            display: flex;
        }
        .form{
            padding-left: 30px;
            padding-right: 30px;
        }
        .display{
            padding-left: 30px;
        }
    </style>
</head>
<body>
 <?php
// データベースに接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $e_n = "";
    $edit_name = "";
    $edit_com = "";
    $edit_pass = "";

// 指定番号の削除処理
    if(!empty($_POST["delete_num"]) && !empty($_POST["password_2"])){
        $id = $_POST["delete_num"];
        $password = $_POST["password_2"];
        // データベースから値を取得
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $ro){
            // データベースのIDとパスワードが入力した値と一致したら削除する
            if($ro['id'] == $id && $ro['password'] == $password){
                $sql = 'delete from mission5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    
// フォームに元の値を表示させる処理
    if(!empty($_POST["edit_num"]) && !empty($_POST["password_3"])){
        $id = $_POST["edit_num"];
        $password = $_POST["password_3"];
        // データベースから値を取得
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $ro){
            // データベースのIDとパスワードが入力した値と一致したら表示させる
            if ($ro['id'] == $id && $ro['password'] == $password){
                $e_n = $ro['id'];
                $edit_name = $ro['name'];
                $edit_com = $ro['comment'];
                $edit_pass = $ro['password'];
            }
        }
    }
    
// 編集処理
    if (!empty($_POST["e_post"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password_1"])){
        $id = $_POST["e_post"];
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $updated_at = date( "Y/m/d  H:i:s" );
        $password = $_POST["password_1"];
        // レコードの更新処理   
        $sql = 'UPDATE mission5 SET name=:name, comment=:comment, updated_at=:updated_at, password=:password WHERE id=:id';
        $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }
// ファイルへの新規書き込み処理
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password_1"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $updated_at = date( "Y/m/d  H:i:s" );
        $password = $_POST["password_1"];
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, updated_at, password) VALUES (:name, :comment, :updated_at, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> execute();
    }
    
?>
<h2>この掲示板のテーマは好きな食べ物</h2>
<div class="forms">
    <div class="form">
        <h3>入力フォーム</h3>
        <form method="POST" action="">
    	    <input type="text" name="name" placeholder="名前を入力" value="<?php echo $edit_name;?>"><br>
    	    <input type="text" name="comment" placeholder="コメントを入力" value="<?php echo $edit_com;?>"><br>
    	    <input type="text" name="password_1" placeholder="パスワードを入力" value="<?php echo $edit_pass;?>"><br>
    	    <input type="submit" name="submit" value="送信"><br>
    	    <input type="hidden" name="e_post" value="<?php echo $e_n;?>"><br>
        </form>
    </div>
    <div class="form">
        <h3>削除フォーム</h3>
        <form method="POST" action="">
	        <input type="number" name="delete_num" placeholder="削除番号を入力"><br>
	        <input type="text" name="password_2" placeholder="パスワードを入力"><br>
	        <input type="submit" name="submit" value="削除"><br>
	    </form>
    </div>
    <div class="form">
        <h3>編集フォーム</h3>
        <form method="POST" action="">
        	<input type="number" name="edit_num" placeholder="編集対象番号を入力"><br>
        	<input type="text" name="password_3" placeholder="パスワードを入力"><br>
        	<input type="submit" name="submit" value="編集"><br>
        </form>
    </div>
</div>
<br>
<hr>
<br>
<div class="display">
    <?php
    //  ファイル内容の表示
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].' '.$row['name'].' '.$row['comment'].' '.$row['updated_at'].'<br>';
        }
    ?>
</div>
</body>