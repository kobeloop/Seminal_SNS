<?php
	session_start();

	header("Content-type: text/html; charset=utf-8");

	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
	if ($_POST['token'] != $_SESSION['token']){
		echo "不正アクセスの可能性あり";
		exit();
	}

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');

	//データベース接続
	require_once("db.php");
	$dbh = db_connect();

	//エラーメッセージの初期化
	$errors = array();

	if(empty($_POST)) {
		header("Location: toi_input.php");
		exit();
	}

	$mail = $_SESSION['mail'];
	$account = $_SESSION['account'];
	$inquiry = $_SESSION['inquiry'];

	//ここでデータベースに登録する
	try{
		//例外処理を投げる（スロー）ようにする
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		//トランザクション開始
		$dbh->beginTransaction();
	
		//memberテーブルに本登録する
		$statement = $dbh->prepare("INSERT INTO toi (account,mail,inquiry) VALUES (:account,:mail,:inquiry)");
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':account', $account, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->bindValue(':inquiry', $inquiry, PDO::PARAM_STR);
		$statement->execute();
		
		// トランザクション完了（コミット）
		$dbh->commit();
		
		//データベース接続切断
		$dbh = null;
	
		//セッション変数を全て解除
		$_SESSION = array();
	
		//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
		if (isset($_COOKIE["PHPSESSID"])) {
    			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
 		//セッションを破棄する
 		session_destroy();
 	
 		/*
 		登録完了のメールを送信
 		*/
	
	}catch (PDOException $e){
		//トランザクション取り消し（ロールバック）
		$dbh->rollBack();
		$errors['error'] = "もう一度やりなおして下さい。";
		print('Error:'.$e->getMessage());
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>お問い合わせ完了画面</title>
		<meta charset="utf-8">
	</head>
	<body>
 
		<?php if (count($errors) === 0): ?>
		<h1>お問い合わせ完了画面</h1>

		<p>お問い合わせが完了いたしました。メイン画面にどうぞ。</p>
		<p><a href="login_admin.php">メイン画面</a></p>

		<?php elseif(count($errors) > 0): ?>

		<?php
			foreach($errors as $value){
				echo "<p>".$value."</p>";
		}
		?>

		<?php endif; ?>
 
	</body>
</html>