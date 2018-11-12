<?php
	session_start();
	header("Content - type: text/html; charset = utf-8");
	
	// クロスサイトリクエストフォージェリ(CSRF)対策
	for($i = 0; $i < 64; $i++){
		$_SESSION['token'] = $_SESSION['token']. substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1); 
	}
// 	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];
	
	// クリックジャッキング対策
	header('X - FRAME - OPTIONS: SAMEORIGN');
	
?>
<!DOCTYPE html>
<html lang = "ja">
<html>
	<head>
		<meta charset = "UTF-8">
		<title>投稿画面	</title>
	</head>
	<body>
		<h1>投稿フォーム</h1>
		<form action ="tweet_check.php" method = "POST">
			<table align="center">
				<tr>
					<td>学生証番号</td>
					<td><input type = "text" name = "account" size = "50" placeholder="例）6bss○○○○"></td>
				</tr>
				<tr>
					<td>投稿内容</td>
					<td><textarea name="inquiry" cols="50" rows="5"></textarea></td>
				</tr>
			</table>
			<div style = "margin-left:10%;margin-right:10%;">
				<center><input type="submit" value="確認する" style = "padding:10px;font-size:30px;background:#6699CC;color:white"></center>
			</div>
			<input type="hidden" name="token" value="<?=$token ?>">
		</form>
	</body>
</html>