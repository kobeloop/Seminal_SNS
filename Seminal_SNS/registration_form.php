<?php
	session_start();

	header("Content-type: text/html; charset=utf-8");

	//クロスサイトリクエストフォージェリ（CSRF）対策
	for($i = 0; $i < 64; $i++){
		$_SESSION['token'] = $_SESSION['token']. substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1); 
	}
// 	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');

	//データベース接続
	require_once("db.php");
	$dbh = db_connect();

	//エラーメッセージの初期化
	$errors = array();

	if(empty($_GET)) {
		header("Location: mail_form.php");
		exit();
	}else {
		//GETデータを変数に入れる
		$urltoken = isset($_GET[urltoken]) ? $_GET[urltoken] : NULL;
		//メール入力判定
		if ($urltoken == ''){
			$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
		}else {
			try{
				//例外処理を投げる（スロー）ようにする
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
				//flagが0の未登録者・仮登録日から24時間以内
				$statement = $dbh->prepare("SELECT mail FROM pre_member WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
				$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
				$statement->execute();
			
				//レコード件数取得
				$row_count = $statement->rowCount();
			
				//24時間以内に仮登録され、本登録されていないトークンの場合
				if( $row_count ==1){
					$mail_array = $statement->fetch();
					$mail = $mail_array[mail];
					$_SESSION['mail'] = $mail;
				}else {
					$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
				}
			
				//データベース接続切断
				$dbh = null;
			
			}catch (PDOException $e){
				print('Error:'.$e->getMessage());
				die();
			}
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>会員登録画面</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>会員登録画面</h1>

		<?php if (count($errors) === 0): ?>

		<form action="registration_check.php" method="post">

			<table>
				<tr>
					<td>メールアドレス</td>
					<td><?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></td>
				</tr>
				<tr>
					<td>学生証番号</td>
					<td><input type="text" name="account"><td>
				</tr>
				<tr>
					<td>パスワード</td>
					<td><input type="text" name="password"></td>
				</tr>
			</table>
				
<!-- 			<p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p> -->
<!-- 			<p>アカウント名：<input type="text" name="account"></p> -->
<!-- 			<p>パスワード：<input type="text" name="password"></p> -->
 
			<input type="hidden" name="token" value="<?=$token?>">
			<input type="submit" value="確認する"style = "padding:10px;font-size:20px;background:#6699CC; color:white">
 
		</form>
 
		<?php elseif(count($errors) > 0): ?>

		<?php
			foreach($errors as $value){
				echo "<p>".$value."</p>";
			}
		?>

		<?php endif; ?>

	</body>
</html>