<?php
	session_start();
	header("Content - type: text/html; charset = utf-8");
	
	// クロスサイトリクエストフォージェリ(CSRF)対策のトークン
	if ($_POST['token'] != $_SESSION['token']){
		echo "不正アクセスの可能性あり";
	}
	
	// クリックジャッキング対策
	header('X - FRAME - OPTIONS: SAMEORIGN');
	
	// データベース接続
	require_once("db.php");
	$dbh = db_connect();
	
	// エラーメッセージの初期化
	$errors = array();
	
	if (empty($_POST)){
		header("Location: mail_form.php");
		exit();
	} else{
		// POSTされたデータを変数に入れる
		$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
		
		// メール入力判定
		if ($mail == ''){
			$errors['mail'] = "メールアドレスが入力されていません";
		} else {
			if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
				$errors['mail_cheak'] = "メールアドレスの形式が正しくありません";
			}
			foreach(as $value){
				
			/*
			ここで本登録のmenberテーブルにすでに登録されているmailかどうかをチェックする。
			$errors['member_cheak'] = "このメールアドレスは使用されてます";
			*/
		}
	}
	
	if (count($errors) === 0){
		$urltoken = hash('sha256', uniqid(rand(), 1));
		$url = "http://tt-23.99sv-coco.com/mission_6-1/registration_form.php". "?urltoken=". $urltoken;
		
		// ここでデータベースに登録する
		try{
			// 例外処理を投げる（スロー）ようにする
			$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement = $dbh -> prepare("INSERT INTO pre_member(urltoken, mail, date) VALUES (:urltoken, :mail, now() )");
			
			// ブレースホルダへ実際の値を設定する
			$statement -> bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement -> bindValue(':mail', $mail, PDO::PARAM_STR);
			$statement -> execute();
			
			// データベース接続解除
			$dbh = null;
			
		} catch (PDOException $e){
			print ('Error:'. $e -> getMessage());
			die();
		}
		
		// メールの宛先
		$mailTo = $mail;
		
		// Return-Pathに指定するメールアドレス
		$returnMail = 'web@sample.com';
		
		$name = "ゼミナールSNS";
		$mail = 'web@sample.com';
		$subject = "【ゼミナールSNS】会員登録用URLのお知らせ";
		
$body = <<< EOM
24時間以内に下記のURLからご登録ください。
{$url }
EOM;
		
		mb_language('ja');
		mb_internal_encoding('UTF-8');
		
		// Fromヘッダーを作成
		$header = 'From: '. mb_encode_mimeheader($name). ' <'. $mail. '>';
		
		if (mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)) {
			
			// セッション変数を全て解除
			$_SESSION = array();
			
			// クッキーの削除
			if (isset($_COOKIE["PHPSESSID"])) {
				setcookie("PHPSESSID", ' ', time() - 1800, '/');
			}
			
			// セッションを破壊する
			session_destroy;
			
			$message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録ください。";
		} else {
			$errors['mail_error'] = "メールの送信に失敗しました。";
		}
	}
?>

<!DOCTYPE html>
<html lang = "ja">
<html>
	<head>
		<meta charset = "UTF-8">
		<title>メール確認画面</title>
	</head>
	<body>
		<h1>メール確認画面</h1>
		<?php if (count($errors) === 0): ?>
		
		<p><?=$message ?></p>
		
		<p>このURLが記載されたメールが届きます。</p>
		<a href = "<?=$url ?>"><?=$url ?></a>
		
		<?php elseif(count($errors) > 0): ?>
		
		<?php
			foreach($errors as $value){
				echo "<p>". $value. "</p>";
			}
		?>
		
		<input type = "button" value = "戻る" onClick = "history.back()" style = "padding:10px;font-size:20px;background:#6699CC;color:white">
		
		<?php endif; ?>
	</body>
</html>